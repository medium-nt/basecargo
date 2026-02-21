<?php

namespace App\Services;

use App\Http\Requests\CargoShipmentRequest;
use App\Models\CargoShipment;
use App\Models\User;
use App\Services\DTO\ImportResult;
use App\Services\DTO\ImportValidationResult;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CargoShipmentImportService
{
    /**
     * Распарсить Excel файл в массив данных
     *
     * @return array{headers: array, data: array}
     */
    public function parseFile(UploadedFile $file): array
    {
        try {
            $spreadsheet = IOFactory::load($file->getPathname());
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => 'Невозможно прочитать файл. Проверьте что это корректный файл Excel (.xlsx или .xls).',
            ]);
        } catch (\Exception $e) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => 'Ошибка при чтении файла: '.$e->getMessage(),
            ]);
        }

        $worksheet = $spreadsheet->getActiveSheet();

        // Первая строка - заголовки
        $headers = $this->extractHeaders($worksheet);

        // Данные - со 2-й строки, лимит 100
        $data = $this->extractData($worksheet);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return [
            'headers' => $headers,
            'data' => $data,
        ];
    }

    /**
     * Извлечь заголовки из первой строки
     */
    private function extractHeaders(Worksheet $worksheet): array
    {
        $headers = [];
        foreach ($worksheet->getRowIterator(1, 1) as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $headers[$cell->getColumn()] = $cell->getValue();
            }
        }

        return $headers;
    }

    /**
     * Извлечь данные из Excel (со 2-й строки)
     */
    private function extractData(Worksheet $worksheet): array
    {
        $data = [];
        $rowCount = 0;
        $maxRows = 100;

        foreach ($worksheet->getRowIterator(2) as $row) {
            if ($rowCount >= $maxRows) {
                break;
            }

            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[$cell->getColumn()] = $cell->getValue();
            }

            // Пропускаем пустые строки - должно быть заполнено хотя бы одно обязательное поле
            // Обязательные поля: cargo_number(A), product_name(B), packaging(D), places_count(E),
            //                       gross_weight_total(I), volume_total(N)
            $requiredFields = ['A', 'B', 'D', 'E', 'I', 'N'];
            $hasRequiredData = false;

            foreach ($requiredFields as $column) {
                $value = $rowData[$column] ?? null;
                if ($value !== null && $value !== '') {
                    $hasRequiredData = true;
                    break;
                }
            }

            if ($hasRequiredData) {
                $data[] = $rowData;
                $rowCount++;
            }
        }

        return $data;
    }

    /**
     * Автоопределение маппинга по заголовкам
     *
     * @param  array  $fileHeaders  Заголовки из файла [columnLetter => headerLabel]
     * @return array Маппинг [columnLetter => fieldName|'skip']
     */
    public function detectMapping(array $fileHeaders): array
    {
        $columnDefinitions = $this->getColumnDefinitions();
        $mapping = [];

        foreach ($fileHeaders as $columnLetter => $headerLabel) {
            $matchedField = null;

            // Ищем совпадение по метке
            // Убираем возможную звёздочку из заголовка файла (обязательные поля)
            $cleanHeaderLabel = preg_replace('/^\*\s*/u', '', trim($headerLabel));

            foreach ($columnDefinitions as $field => $label) {
                if ($cleanHeaderLabel === trim($label)) {
                    $matchedField = $field;
                    break;
                }
            }

            // Если не нашли - пропускаем
            $mapping[$columnLetter] = $matchedField ?? 'skip';
        }

        return $mapping;
    }

    /**
     * Применить маппинг к сырым данным
     *
     * @param  array  $rawData  Сырые данные из Excel
     * @param  array  $mapping  Маппинг колонок
     * @return array Трансформированные данные
     */
    public function applyMapping(array $rawData, array $mapping): array
    {
        $mapped = [];

        foreach ($rawData as $rowIndex => $row) {
            $mappedRow = [];

            foreach ($mapping as $columnLetter => $field) {
                if ($field === 'skip') {
                    continue;
                }

                $value = $row[$columnLetter] ?? null;

                // Конвертация типов
                $mappedRow[$field] = $this->convertValueType($field, $value);
            }

            // Сохраняем индекс строки для отображения ошибок
            $mappedRow['_row_index'] = $rowIndex + 2; // +2 потому что: 0-based index + заголовок

            $mapped[] = $mappedRow;
        }

        return $mapped;
    }

    /**
     * Конвертация значения в нужный тип
     */
    private function convertValueType(string $field, mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        // cargo_number всегда как строка
        if ($field === 'cargo_number') {
            return (string) $value;
        }

        // Целочисленные поля
        $intFields = ['places_count', 'items_per_place', 'total_items_count', 'client_id', 'responsible_user_id'];
        if (in_array($field, $intFields)) {
            return is_numeric($value) ? (int) $value : null;
        }

        // Числовые поля
        $numericFields = [
            'volume_total', 'volume_per_item',
            'gross_weight_total', 'gross_weight_per_place',
            'net_weight_total', 'net_weight_per_box',
            'tare_weight_total', 'tare_weight_per_box',
            'length', 'width', 'height',
            'china_cost', 'insurance_amount', 'insurance_cost',
            'payment', 'ITS', 'duty', 'VAT', 'volume_weight',
            'customs_clearance_service', 'cost_truck',
            'revenue_per_kg', 'dollar_rate', 'yuan_rate', 'rate_rub', 'total_cost',
            'estimated_value_cargo_ITS', 'total_payment', 'importer_services', 'delivery_to_Ussuriysk',
        ];
        if (in_array($field, $numericFields)) {
            return is_numeric($value) ? (float) $value : null;
        }

        return $value;
    }

    /**
     * Валидировать строки по правилам CargoShipmentRequest
     */
    public function validateRows(array $mappedData): ImportValidationResult
    {
        $result = new ImportValidationResult;
        $result->totalRows = count($mappedData);

        $request = new CargoShipmentRequest;
        $rules = $request->rules();
        $messages = $request->messages();

        // Собираем все public_id для проверки существующих грузов
        $publicIds = [];
        foreach ($mappedData as $row) {
            if (! empty($row['public_id'])) {
                $publicIds[] = $row['public_id'];
            }
        }

        // Загружаем существующие грузы
        $existingShipments = [];
        if (! empty($publicIds)) {
            $existingShipments = CargoShipment::whereIn('public_id', $publicIds)
                ->get()
                ->keyBy('public_id');
        }

        foreach ($mappedData as $index => $row) {
            // Убираем временный поле _row_index из валидации
            $dataForValidation = array_diff_key($row, ['_row_index' => true]);

            $validator = Validator::make($dataForValidation, $rules, $messages);

            if ($validator->fails()) {
                $result->errors[$index] = $validator->errors()->toArray();
            } else {
                $result->validRows[] = $row;

                // Проверяем public_id для warnings
                if (! empty($row['public_id']) && isset($existingShipments[$row['public_id']])) {
                    $shipment = $existingShipments[$row['public_id']];
                    $rowIndex = $row['_row_index'] ?? '?';

                    // Если статус не позволяет обновление
                    $status = $shipment->cargo_status ?? '(не указан)';
                    if (empty($shipment->cargo_status) || ! in_array($shipment->cargo_status, ['wait_payment', 'shipping_supplier'])) {
                        $result->warnings[$index] = "Груз '{$shipment->cargo_number}' (статус: {$status}) не может быть обновлён. Строка будет пропущена.";
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Сохранить валидные грузы в БД
     */
    public function saveShipments(array $validRows, User $user): ImportResult
    {
        $result = new ImportResult;

        // Для агентов - игнорируем public_id, всегда создаем новые грузы
        if ($user->isAgent()) {
            foreach ($validRows as $row) {
                try {
                    $rowIndex = $row['_row_index'] ?? '?';
                    unset($row['_row_index'], $row['public_id']);
                    $this->createNewShipment($row, $user);
                    $result->createdCount++;
                } catch (\Exception $e) {
                    $result->errors[] = "Строка {$rowIndex}: ".$e->getMessage();
                }
            }

            return $result;
        }

        // Собираем все public_id для поиска одним запросом (избегаем N+1)
        $publicIds = [];
        foreach ($validRows as $row) {
            if (! empty($row['public_id'])) {
                $publicIds[] = $row['public_id'];
            }
        }

        // Загружаем все существующие грузы одним запросом
        $existingShipments = [];
        if (! empty($publicIds)) {
            $existingShipments = CargoShipment::whereIn('public_id', $publicIds)
                ->get()
                ->keyBy('public_id');
        }

        foreach ($validRows as $row) {
            try {
                $rowIndex = $row['_row_index'] ?? '?';
                unset($row['_row_index']);

                if (! empty($row['public_id'])) {
                    $shipment = $existingShipments[$row['public_id']] ?? null;

                    if ($shipment) {
                        // Проверяем статус для обновления (пустой статус не позволяет обновление)
                        if (! empty($shipment->cargo_status) && in_array($shipment->cargo_status, ['wait_payment', 'shipping_supplier'])) {
                            // Проверка прав на обновление через Policy
                            if (! \Gate::forUser($user)->allows('update', $shipment)) {
                                $result->errors[] = "Строка {$rowIndex}: нет прав для обновления груза {$shipment->cargo_number}";

                                continue;
                            }

                            // При обновлении никогда не меняем responsible_user_id
                            unset($row['public_id'], $row['responsible_user_id']);
                            $shipment->update($row);
                            $result->updatedCount++;
                        } else {
                            // Статус не позволяет обновление - пропускаем (предупреждение уже есть на preview)
                            continue;
                        }
                    } else {
                        // public_id указан, но груз не найден - создаем новый
                        $this->createNewShipment($row, $user);
                        $result->createdCount++;
                    }
                } else {
                    // Создаем новый груз
                    $this->createNewShipment($row, $user);
                    $result->createdCount++;
                }
            } catch (\Exception $e) {
                $result->errors[] = "Строка {$rowIndex}: ".$e->getMessage();
            }
        }

        return $result;
    }

    /**
     * Создать новый груз
     */
    private function createNewShipment(array $row, User $user): void
    {
        // Устанавливаем ответственного
        if ($user->isAgent() || $user->isManager()) {
            $row['responsible_user_id'] = $user->id;
        }

        // public_id сгенерируется автоматически в boot() модели
        CargoShipment::create($row);
    }

    /**
     * Получить определения колонок из шаблона экспорта
     *
     * @return array[fieldName => label]
     */
    public function getColumnDefinitions(): array
    {
        return [
            'public_id' => 'Public ID',
            'cargo_number' => 'номер груза (货物编号)',
            'product_name' => 'наименование товара (产品名称)',
            'material' => 'материал (材料)',
            'packaging' => 'упаковка (包装)',
            'places_count' => 'количество мест (座位数目)',
            'items_per_place' => 'количество товары/мест (产品/地点数目)',
            'total_items_count' => 'общее количество штук (件总数)',
            'gross_weight_per_place' => 'вес брутто 1 места (1个座位的毛重)',
            'gross_weight_total' => 'Общий вес брутто (总毛重)',
            'length' => 'длина',
            'width' => 'ширина',
            'height' => 'высота',
            'volume_per_item' => 'обьем 1 места (1个座位的体积)',
            'volume_total' => 'общий обьем кубов (立方体的总体积)',
            'tare_weight_per_box' => 'вес 1 тары (1皮重)',
            'tare_weight_total' => 'вес всех коробок (所有箱子的重量)',
            'net_weight_per_box' => 'Вес нетто 1 коробки (净重1箱)',
            'net_weight_total' => 'Общий вес нетто (总净重)',
            'explanations' => 'поясниния',
            'TN_VED_code' => 'код ТНВЭД',
            'ITS' => 'ИТС',
            'payment' => 'платеж',
            'label_name' => 'НИМЕНОВАНИЕ ДЛЯ БИРКИ',
            'label_number' => 'номер бирки',
            'marking' => 'МАРКИРОВКА',
            'manufacturer' => 'ИЗГОТОВИТЕЛЬ',
            'SS_DS' => 'СС/ДС',
            'estimated_value_cargo_ITS' => 'стоимость груза по ИТС $',
            'total_payment' => 'Общий платеж $',
            'importer_services' => 'услуги импортера $',
            'delivery_to_Ussuriysk' => 'доставка общая до Уссурийска ₽',
            'revenue' => 'выручка ₽',
            'total' => 'Итого ₽',
            'total_per_kg' => 'Итого за КГ ¥',
        ];
    }
}
