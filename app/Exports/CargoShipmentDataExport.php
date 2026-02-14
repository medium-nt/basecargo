<?php

namespace App\Exports;

use App\Models\CargoShipment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CargoShipmentDataExport
{
    private array $cargoIds;

    public function __construct(array $cargoIds)
    {
        $this->cargoIds = $cargoIds;
    }

    public function download(): BinaryFileResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A' => ['field' => 'cargo_number', 'label' => 'номер груза (货物编号)'],
            'B' => ['field' => 'product_name', 'label' => 'наименование товара (产品名称)'],
            'C' => ['field' => 'material', 'label' => 'материал (材料)'],
            'D' => ['field' => 'packaging', 'label' => 'упаковка (包装)'],
            'E' => ['field' => 'places_count', 'label' => 'количество мест (座位数目)'],
            'F' => ['field' => 'items_per_place', 'label' => 'количество товары/мест (产品/地点数目)'],
            'G' => ['field' => 'total_items_count', 'label' => 'общее количество штук (件总数)'],
            'H' => ['field' => 'gross_weight_per_place', 'label' => 'вес брутто 1 места (1个座位的毛重)'],
            'I' => ['field' => 'gross_weight_total', 'label' => 'Общий вес брутто (总毛重)'],
            'J' => ['field' => 'length', 'label' => 'длина'],
            'K' => ['field' => 'width', 'label' => 'ширина'],
            'L' => ['field' => 'height', 'label' => 'высота'],
            'M' => ['field' => 'volume_per_item', 'label' => 'обьем 1 места (1个座位的体积)'],
            'N' => ['field' => 'volume_total', 'label' => 'общий обьем кубов (立方体的总体积)'],
            'O' => ['field' => 'tare_weight_per_box', 'label' => 'вес 1 тары (1皮重)'],
            'P' => ['field' => 'tare_weight_total', 'label' => 'вес всех коробок (所有箱子的重量)'],
            'Q' => ['field' => 'net_weight_per_box', 'label' => 'Вес нетто 1 коробки (净重1箱)'],
            'R' => ['field' => 'net_weight_total', 'label' => 'Общий вес нетто (总净重)'],
            'S' => ['field' => 'explanations', 'label' => 'поясниния'],
            'T' => ['field' => 'TN_VED_code', 'label' => 'код ТНВЭД'],
            'U' => ['field' => 'ITS', 'label' => 'ИТС'],
            'V' => ['field' => 'payment', 'label' => 'платеж'],
            'W' => ['field' => 'label_name', 'label' => 'НИМЕНОВАНИЕ ДЛЯ БИРКИ'],
            'X' => ['field' => 'label_number', 'label' => 'номер бирки'],
            'Y' => ['field' => 'marking', 'label' => 'МАРКИРОВКА'],
            'Z' => ['field' => 'manufacturer', 'label' => 'ИЗГОТОВИТЕЛЬ'],
            'AA' => ['field' => 'SS_DS', 'label' => 'СС/ДС'],
            'AB' => ['field' => 'estimated_value_cargo_ITS', 'label' => 'стоимость груза по ИТС $'],
            'AC' => ['field' => 'total_payment', 'label' => 'Общий платеж $'],
            'AD' => ['field' => 'importer_services', 'label' => 'услуги импортера $'],
            'AE' => ['field' => 'delivery_to_Ussuriysk', 'label' => 'доставка общая до Уссурийска ₽'],
            'AF' => ['field' => 'revenue', 'label' => 'выручка ₽'],
            'AG' => ['field' => 'total', 'label' => 'Итого ₽'],
            'AH' => ['field' => 'total_per_kg', 'label' => 'Итого за КГ ¥'],
        ];

        // Заголовки
        $row = 1;
        foreach ($headers as $col => $data) {
            $cell = $col.$row;
            $sheet->setCellValue($cell, $data['label']);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cell)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($cell)->getAlignment()->setWrapText(true);
        }

        // Данные
        $shipments = CargoShipment::query()
            ->whereIn('id', $this->cargoIds)
            ->get();

        // Вычисляемые поля - только формулы
        $formulaFields = [
            'H' => 'gross_weight_per_place',
            'M' => 'volume_per_item',
            'Q' => 'net_weight_per_box',
            'O' => 'tare_weight_per_box',
            'P' => 'tare_weight_total',
        ];

        $dataRow = 2;
        foreach ($shipments as $shipment) {
            foreach ($headers as $col => $data) {
                $field = $data['field'];
                // Пропускаем вычисляемые поля - они заполнятся формулами позже
                if (isset($formulaFields[$col])) {
                    continue;
                }
                $value = $shipment->$field ?? '';
                $sheet->setCellValue($col.$dataRow, $value);
            }
            $dataRow++;
        }

        // Разблокировать все ячейки по умолчанию
        $sheet->getParent()->getDefaultStyle()->getProtection()->setLocked(false);

        // Формулы для вычисляемых полей (с проверкой на пустые + округление до 3 знаков)
        $formulaFields = [
            'H' => ['col' => 'I', 'op' => '/'],
            'M' => ['col' => 'N', 'op' => '/'],
            'Q' => ['col' => 'R', 'op' => '/'],
            'O' => ['col' => 'H', 'op' => '-', 'field2' => 'Q'],
            'P' => ['col' => 'I', 'op' => '-', 'field2' => 'R'],
        ];

        // Список ячеек с формулами для визуального выделения
        $formulaCells = [];

        $dataRow = 2;
        foreach ($shipments as $shipment) {
            foreach ($formulaFields as $col => $formulaData) {
                $cell = $col.$dataRow;
                // Формулы с проверкой IF(OR(...)) и ROUND(..., 3)
                if ($col === 'H' || $col === 'M' || $col === 'Q') {
                    $formula = "=IF(OR({$formulaData['col']}{$dataRow}=\"\", E{$dataRow}=\"\"), \"\", ROUND({$formulaData['col']}{$dataRow}{$formulaData['op']}E{$dataRow}, 3))";
                } elseif ($col === 'O') {
                    $formula = "=IF(OR(H{$dataRow}=\"\", {$formulaData['field2']}{$dataRow}=\"\"), \"\", ROUND(H{$dataRow}{$formulaData['op']}{$formulaData['field2']}{$dataRow}, 3))";
                } elseif ($col === 'P') {
                    $formula = "=IF(OR(I{$dataRow}=\"\", {$formulaData['field2']}{$dataRow}=\"\"), \"\", ROUND(I{$dataRow}{$formulaData['op']}{$formulaData['field2']}{$dataRow}, 3))";
                }
                $sheet->setCellValue($cell, $formula);
                $sheet->getStyle($cell)->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);
                $sheet->getStyle($cell)->getProtection()->setHidden(true);
                $formulaCells[] = $cell;
            }
            $dataRow++;
        }

        // Визуальное выделение ячеек с формулами (ТОЛЬКО записанные формулы)
        foreach ($formulaCells as $cell) {
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2EFDA');
            $sheet->getStyle($cell)->getFont()->setItalic(true);
            $sheet->getStyle($cell)->getFont()->getColor()->setRGB('008000');
        }

        // Автоширина колонок
        foreach ($headers as $col => $data) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Защита листа
        $sheet->getProtection()->setSheet(true);
        $sheet->getProtection()->setInsertRows(false);
        $sheet->getProtection()->setDeleteRows(false);

        $sheet->setTitle('Экспорт грузов');

        $filename = 'cargo_shipments_export_'.date('Y-m-d_His').'.xlsx';
        $tempFile = sys_get_temp_dir().'/'.$filename;
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet, $writer);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}
