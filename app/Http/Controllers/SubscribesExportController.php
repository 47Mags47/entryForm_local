<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Division;

use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SubscribesExportController
{
    public function index(Request $request, Division $division){
        $query = $division->subscribes()
            ->whereHasAccess()
            ->orderBy('start_at')
            ->where(function($query) use ($request){
                if ($request->has('from')) {
                    $query->where('start_at', '>=', Carbon::parse($request->input('from')));

                    if (!$request->has('to') && Carbon::parse($request->input('from'))->day === 1)
                        $query->where('start_at', '<=', Carbon::parse($request->input('from'))->endOfMonth());
                }
                else
                    $query->where('start_at', '>=', now()->startOfMonth()->startOfDay());

                if ($request->has('to'))
                    $query->where('start_at', '<=', Carbon::parse($request->input('to')));
            });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // настройки
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setFitToWidth(true);

        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(11);
        $sheet->getColumnDimension('E')->setWidth(25);

        // Заголовки
        $sheet->setCellValue('A1', 'Клиент');
        $sheet->setCellValue('B1', 'Услуга');
        $sheet->setCellValue('C1', 'Специалист');
        $sheet->setCellValue('D1', 'Кабинет');
        $sheet->setCellValue('E1', 'Время записи');

        $row = 2;

        // Данные
        foreach ($query->cursor() as $subscribe) {
            $sheet->setCellValue("A{$row}", $subscribe->last_name . ' ' . $subscribe->first_name . ' ' . $subscribe->middle_name);
            $sheet->setCellValue("B{$row}", $subscribe->service->name);
            $sheet->setCellValue("C{$row}", $subscribe->worker->last_name . ' ' . $subscribe->first_name . ' ' . $subscribe->middle_name);
            $sheet->setCellValue("D{$row}", $subscribe->worker->office);
            $sheet->setCellValue("E{$row}", $subscribe->start_at);

            $row++;
        }

        // Стили
        // Шапка
        $headerRange = 'A1:E1';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E9EEF5'],
            ],
        ])->getAlignment();

        $sheet->getStyle($headerRange)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_HAIR);

        $sheet->getStyle($headerRange)
            ->getFont()
            ->setSize(16);

        // Тело
        $dataRange = 'A2:E' . ($row - 1);
        $sheet->getStyle($dataRange)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_HAIR);

        $sheet->getStyle($dataRange)
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setWrapText(true);

        $sheet->getStyle($dataRange)
            ->getFont()
            ->setSize(14);

        // Результат
        $writer = new Xlsx($spreadsheet);
        $response = response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'subscribes.xlsx');

        unset($writer);
        unset($spreadsheet);

        return $response;
    }
}
