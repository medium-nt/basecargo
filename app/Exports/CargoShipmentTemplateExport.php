<?php

namespace App\Exports;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CargoShipmentTemplateExport
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function download(): BinaryFileResponse
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A' => ['field' => 'cargo_number', 'label' => 'номер груза (货物编号)', 'required' => true],
            'B' => ['field' => 'product_name', 'label' => 'наименование товара (产品名称)', 'required' => true],
            'C' => ['field' => 'material', 'label' => 'материал (材料)', 'required' => false],
            'D' => ['field' => 'packaging', 'label' => 'упаковка (包装)', 'required' => true],
            'E' => ['field' => 'places_count', 'label' => 'количество мест (座位数目)', 'required' => true],
            'F' => ['field' => 'items_per_place', 'label' => 'количество товары/мест (产品/地点数目)', 'required' => false],
            'G' => ['field' => 'total_items_count', 'label' => 'общее количество штук (件总数)', 'required' => false],
            'H' => ['field' => 'gross_weight_per_place', 'label' => 'вес брутто 1 места (1个座位的毛重)', 'required' => false],
            'I' => ['field' => 'gross_weight_total', 'label' => 'Общий вес брутто (总毛重)', 'required' => true],
            'J' => ['field' => 'length', 'label' => 'длина', 'required' => false],
            'K' => ['field' => 'width', 'label' => 'ширина', 'required' => false],
            'L' => ['field' => 'height', 'label' => 'высота', 'required' => false],
            'M' => ['field' => 'volume_per_item', 'label' => 'обьем 1 места (1个座位的体积)', 'required' => false],
            'N' => ['field' => 'volume_total', 'label' => 'общий обьем кубов (立方体的总体积)', 'required' => true],
            'O' => ['field' => 'tare_weight_per_box', 'label' => 'вес 1 тары (1皮重)', 'required' => false],
            'P' => ['field' => 'tare_weight_total', 'label' => 'вес всех коробок (所有箱子的重量)', 'required' => false],
            'Q' => ['field' => 'net_weight_per_box', 'label' => 'Вес нетто 1 коробки (净重1箱)', 'required' => false],
            'R' => ['field' => 'net_weight_total', 'label' => 'Общий вес нетто (总净重)', 'required' => false],
            'S' => ['field' => 'explanations', 'label' => 'поясниния', 'required' => false],
            'T' => ['field' => 'TN_VED_code', 'label' => 'код ТНВЭД', 'required' => false],
            'U' => ['field' => 'ITS', 'label' => 'ИТС', 'required' => false],
            'V' => ['field' => 'payment', 'label' => 'платеж', 'required' => false],
            'W' => ['field' => 'label_name', 'label' => 'НИМЕНОВАНИЕ ДЛЯ БИРКИ', 'required' => false],
            'X' => ['field' => 'label_number', 'label' => 'номер бирки', 'required' => false],
            'Y' => ['field' => 'marking', 'label' => 'МАРКИРОВКА', 'required' => false],
            'Z' => ['field' => 'manufacturer', 'label' => 'ИЗГОТОВИТЕЛЬ', 'required' => false],
            'AA' => ['field' => 'SS_DS', 'label' => 'СС/ДС', 'required' => false],
            'AB' => ['field' => 'estimated_value_cargo_ITS', 'label' => 'стоимость груза по ИТС $', 'required' => false],
            'AC' => ['field' => 'total_payment', 'label' => 'Общий платеж $', 'required' => false],
            'AD' => ['field' => 'importer_services', 'label' => 'услуги импортера $', 'required' => false],
            'AE' => ['field' => 'delivery_to_Ussuriysk', 'label' => 'доставка общая до Уссурийска ₽', 'required' => false],
            'AF' => ['field' => 'revenue', 'label' => 'выручка ₽', 'required' => false],
            'AG' => ['field' => 'total', 'label' => 'Итого ₽', 'required' => false],
            'AH' => ['field' => 'total_per_kg', 'label' => 'Итого за КГ ¥', 'required' => false],
        ];

        // Для админов и менеджеров добавляем public_id последней колонкой
        if ($this->user->isAdmin() || $this->user->isManager()) {
            $headers['AI'] = ['field' => 'public_id', 'label' => 'Public ID', 'required' => false];
        }

        // Цвета для обозначения обязательности
        $requiredBg = 'FFC7CE';    // Светло-красный для обязательных
        $optionalBg = 'D9E1F2';    // Серо-голубой для необязательных

        $row = 1;
        foreach ($headers as $col => $data) {
            $cell = $col.$row;

            // Звёздочка для обязательных полей
            $label = $data['required'] ? '* '.$data['label'] : $data['label'];
            $sheet->setCellValue($cell, $label);

            $style = $sheet->getStyle($cell);
            $style->getFont()->setBold(true);

            // Разный цвет фона для обязательных/необязательных
            $bgColor = $data['required'] ? $requiredBg : $optionalBg;
            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($bgColor);

            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $style->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $style->getAlignment()->setWrapText(true);

            // ВАЖНО: заголовки должны быть разблокированы
            $style->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

            // Комментарий к ячейке
            $commentText = $data['required']
                ? 'Обязательное поле для заполнения / 必填项'
                : 'Опциональное поле / 可选项';
            $sheet->getComment($cell)
                ->getText()
                ->createTextRun($commentText);
        }

        // ВАЖНО: Сначала разблокировать все ячейки ПО УМОЛЧАНИЮ
        $sheet->getParent()->getDefaultStyle()->getProtection()->setLocked(false);

        // Формулы для вычисляемых полей (строки 2-100)
        // Проверка на пустые значения + округление до 3 знаков
        foreach (range(2, 100) as $row) {
            $formulas = [
                'H' => "=IF(OR(E{$row}=\"\", I{$row}=\"\"), \"\", ROUND(I{$row}/E{$row}, 3))",  // gross_weight_per_place
                'M' => "=IF(OR(E{$row}=\"\", N{$row}=\"\"), \"\", ROUND(N{$row}/E{$row}, 3))",  // volume_per_item
                'Q' => "=IF(OR(E{$row}=\"\", R{$row}=\"\"), \"\", ROUND(R{$row}/E{$row}, 3))",  // net_weight_per_box
                'O' => "=IF(OR(H{$row}=\"\", Q{$row}=\"\"), \"\", ROUND(H{$row}-Q{$row}, 3))",  // tare_weight_per_box
                'P' => "=IF(OR(I{$row}=\"\", R{$row}=\"\"), \"\", ROUND(I{$row}-R{$row}, 3))",  // tare_weight_total
            ];

            foreach ($formulas as $col => $formula) {
                $cell = $col.$row;
                $sheet->setCellValue($cell, $formula);
                // ЗАБЛОКИРОВАТЬ и СКРЫТЬ формулы
                $sheet->getStyle($cell)->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);
                $sheet->getStyle($cell)->getProtection()->setHidden(true);
            }
        }

        // Визуальное выделение всех ячеек с формулами (светло-зелёный фон + курсив + зелёный текст)
        $formulaRanges = ['H2:H100', 'M2:M100', 'O2:P100', 'Q2:Q100'];
        foreach ($formulaRanges as $range) {
            $sheet->getStyle($range)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('E2EFDA');
            $sheet->getStyle($range)->getFont()->setItalic(true);
            $sheet->getStyle($range)->getFont()->getColor()->setRGB('008000');
        }

        // Ограничить файл 100 строками (удаляем строки после 100-й)
        $highestRow = $sheet->getHighestRow();
        if ($highestRow > 100) {
            $sheet->removeRow(101, $highestRow - 100);
        }

        foreach ($headers as $col => $data) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Включить защиту листа с запретом изменения структуры
        $sheet->getProtection()->setSheet(true);
        $sheet->getProtection()->setInsertRows(false);    // Запретить вставку строк
        $sheet->getProtection()->setDeleteRows(false);   // Запретить удаление строк
        $sheet->getProtection()->setInsertColumns(false); // Запретить вставку столбцов
        $sheet->getProtection()->setDeleteColumns(false); // Запретить удаление столбцов

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
