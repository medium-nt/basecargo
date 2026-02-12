<?php

namespace App\Exports;

use App\Models\CargoShipment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
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
            'A' => ['field' => 'cargo_number', 'label' => 'Номер груза (货物编号)'],
            'B' => ['field' => 'product_name', 'label' => 'Наименование товара (商品名称)'],
            'C' => ['field' => 'material', 'label' => 'Материал (材料)'],
            'D' => ['field' => 'packaging', 'label' => 'Упаковка (包装)'],
            'E' => ['field' => 'places_count', 'label' => 'Количество мест (座位数目)'],
            'F' => ['field' => 'items_per_place', 'label' => 'Товаров в месте (每件商品数)'],
            'G' => ['field' => 'total_items_count', 'label' => 'Общее количество (总数)'],
            'H' => ['field' => 'gross_weight_total', 'label' => 'Общий вес брутто (кг) (总毛重)'],
            'I' => ['field' => 'net_weight_total', 'label' => 'Общий вес нетто (кг) (总净重)'],
            'J' => ['field' => 'volume_total', 'label' => 'Общий объем (м³) (总体积)'],
            'K' => ['field' => 'length', 'label' => 'Длина (см) (长度)'],
            'L' => ['field' => 'width', 'label' => 'Ширина (см) (宽度)'],
            'M' => ['field' => 'height', 'label' => 'Высота (см) (高度)'],
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

        $dataRow = 2;
        foreach ($shipments as $shipment) {
            foreach ($headers as $col => $data) {
                $field = $data['field'];
                $value = $shipment->$field ?? '';
                $sheet->setCellValue($col.$dataRow, $value);
            }
            $dataRow++;
        }

        // Автоширина колонок
        foreach ($headers as $col => $data) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

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
