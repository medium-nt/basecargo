<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CargoShipmentTemplateExport
{
    public function download(): BinaryFileResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A' => ['field' => 'cargo_number', 'label' => 'Номер груза (货物编号)', 'required' => true],
            'B' => ['field' => 'product_name', 'label' => 'Наименование товара (商品名称)', 'required' => true],
            'C' => ['field' => 'material', 'label' => 'Материал (材料)', 'required' => false],
            'D' => ['field' => 'packaging', 'label' => 'Упаковка (包装)', 'required' => true],
            'E' => ['field' => 'places_count', 'label' => 'Количество мест (座位数目)', 'required' => true],
            'F' => ['field' => 'items_per_place', 'label' => 'Товаров в месте (每件商品数)', 'required' => false],
            'G' => ['field' => 'total_items_count', 'label' => 'Общее количество (总数)', 'required' => false],
            'H' => ['field' => 'gross_weight_total', 'label' => 'Общий вес брутто (кг) (总毛重)', 'required' => true],
            'I' => ['field' => 'net_weight_total', 'label' => 'Общий вес нетто (кг) (总净重)', 'required' => false],
            'J' => ['field' => 'volume_total', 'label' => 'Общий объем (м³) (总体积)', 'required' => true],
            'K' => ['field' => 'length', 'label' => 'Длина (см) (长度)', 'required' => false],
            'L' => ['field' => 'width', 'label' => 'Ширина (см) (宽度)', 'required' => false],
            'M' => ['field' => 'height', 'label' => 'Высота (см) (高度)', 'required' => false],
        ];

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

        foreach ($headers as $col => $data) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Данные для импорта');

        $filename = 'cargo_shipments_template_'.date('Y-m-d').'.xlsx';
        $tempFile = sys_get_temp_dir().'/'.$filename;
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet, $writer);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}
