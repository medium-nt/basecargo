<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportMappingRequest;
use App\Http\Requests\ImportUploadRequest;
use App\Services\CargoShipmentImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CargoShipmentImportController extends Controller
{
    public function __construct(
        private CargoShipmentImportService $service
    ) {}

    /**
     * Шаг 1: Показать форму загрузки файла
     */
    public function create(): View
    {
        return view('cargo_shipments.import.create', [
            'title' => 'Импорт грузов из Excel',
        ]);
    }

    /**
     * Шаг 2: Обработать загрузку файла, распарсить Excel
     */
    public function upload(ImportUploadRequest $request): RedirectResponse
    {
        $file = $request->file('file');

        // Парсим Excel
        $parsed = $this->service->parseFile($file);

        // Сохраняем в session
        session([
            'import.headers' => $parsed['headers'],
            'import.raw_data' => $parsed['data'],
            'import.file_name' => $file->getClientOriginalName(),
        ]);

        // Автоопределяем маппинг
        $detectedMapping = $this->service->detectMapping($parsed['headers']);
        session(['import.detected_mapping' => $detectedMapping]);

        return redirect()->route('cargo_shipments.import.mapping');
    }

    /**
     * Шаг 3: Показать форму маппинга колонок
     */
    public function mapping(): View
    {
        $headers = session('import.headers');
        $rawData = session('import.raw_data');
        $detectedMapping = session('import.detected_mapping', []);

        if (! $headers || ! $rawData) {
            return redirect()
                ->route('cargo_shipments.import.create')
                ->with('error', 'Сессия истекла. Загрузите файл заново.');
        }

        // Получаем первую строку для примера значений
        $sampleRow = $rawData[0] ?? [];

        // Получаем определения колонок
        $columnDefinitions = $this->service->getColumnDefinitions();

        // Определяем колонки, которые есть в файле
        $fileColumns = array_keys($headers);

        return view('cargo_shipments.import.mapping', [
            'title' => 'Сопоставление колонок',
            'fileColumns' => $fileColumns,
            'headers' => $headers,
            'sampleRow' => $sampleRow,
            'columnDefinitions' => $columnDefinitions,
            'detectedMapping' => $detectedMapping,
            'fileName' => session('import.file_name'),
            'totalRows' => count($rawData),
        ]);
    }

    /**
     * Шаг 4: Сохранить маппинг, валидировать строки
     */
    public function validateMapping(ImportMappingRequest $request): RedirectResponse
    {
        $mapping = $request->input('mapping');
        $rawData = session('import.raw_data');

        if (! $rawData) {
            return redirect()
                ->route('cargo_shipments.import.create')
                ->with('error', 'Сессия истекла. Загрузите файл заново.');
        }

        // Применяем маппинг
        $mappedData = $this->service->applyMapping($rawData, $mapping);

        // Валидируем каждую строку
        $validationResult = $this->service->validateRows($mappedData);

        // Сохраняем в session
        session([
            'import.mapping' => $mapping,
            'import.mapped_data' => $mappedData,
            'import.validation_result' => json_encode($validationResult),
        ]);

        return redirect()->route('cargo_shipments.import.preview');
    }

    /**
     * Шаг 5: Показать предпросмотр с ошибками
     */
    public function preview(): View
    {
        $validationResultSerialized = session('import.validation_result');
        $mappedData = session('import.mapped_data');

        if (! $validationResultSerialized || ! $mappedData) {
            return redirect()
                ->route('cargo_shipments.import.create')
                ->with('error', 'Сессия истекла. Начните импорт заново.');
        }

        $validationResult = \App\Services\DTO\ImportValidationResult::fromJson($validationResultSerialized);

        return view('cargo_shipments.import.preview', [
            'title' => 'Предпросмотр импорта',
            'validationResult' => $validationResult,
            'mappedData' => $mappedData,
            'hasErrors' => $validationResult->hasErrors(),
            'totalRows' => $validationResult->totalRows,
            'validRowsCount' => $validationResult->getValidRowsCount(),
            'errorsCount' => $validationResult->getErrorsCount(),
        ]);
    }

    /**
     * Шаг 6: Сохранить валидные грузы
     */
    public function store(Request $request): RedirectResponse
    {
        $validationResultSerialized = session('import.validation_result');

        if (! $validationResultSerialized) {
            return back()->with('error', 'Сессия истекла. Начните импорт заново.');
        }

        $validationResult = \App\Services\DTO\ImportValidationResult::fromJson($validationResultSerialized);

        if ($validationResult->getValidRowsCount() === 0) {
            return back()->with('error', 'Нет валидных строк для импорта');
        }

        $user = auth()->user();
        $result = $this->service->saveShipments($validationResult->validRows, $user);

        // Очищаем session
        $this->clearImportSession();

        $message = "Импорт завершён. Создано: {$result->createdCount}, Обновлено: {$result->updatedCount}";

        if (! empty($result->errors)) {
            $message .= '. Ошибки: '.implode(', ', $result->errors);
        }

        return redirect()
            ->route('cargo_shipments.index')
            ->with('success', $message);
    }

    /**
     * Отмена импорта (очистка session)
     */
    public function cancel(): RedirectResponse
    {
        $this->clearImportSession();

        return redirect()
            ->route('cargo_shipments.index')
            ->with('info', 'Импорт отменён');
    }

    /**
     * Очистить session импорта
     */
    private function clearImportSession(): void
    {
        session()->forget([
            'import.headers',
            'import.raw_data',
            'import.file_name',
            'import.detected_mapping',
            'import.mapping',
            'import.mapped_data',
            'import.validation_result',
        ]);
    }
}
