<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SubscribesExportController
{
    public function index(Request $request, Division $division){
        $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d']
        ]);

        $from = $request->filled('from')
            ? Carbon::parse($request->input('from'))
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->input('to'))
            : now()->endOfMonth();

        $query = $division->subscribes()
            ->whereHasAccess()
            ->orderBy('start_at')
            ->whereBetween('start_at', [$from, $to]);

        $spreadsheet = $spreadsheet = IOFactory::load(storage_path('app/private/templates/subscribes/subscribesIndex.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        // настройки
        $sheet->getPageSetup()->setFitToWidth(true);

        $row = 2;

        // Данные
        foreach ($query->cursor() as $subscribe) {
            $sheet->setCellValue("A{$row}", $subscribe->last_name . ' ' . $subscribe->first_name . ' ' . $subscribe->middle_name);
            $sheet->setCellValue("B{$row}", $subscribe->service->name);
            $sheet->setCellValue("C{$row}", $subscribe->worker->last_name . ' ' . $subscribe->first_name . ' ' . $subscribe->middle_name);
            $sheet->setCellValue("D{$row}", $subscribe->worker->office);
            $sheet->setCellValue("E{$row}", $subscribe->start_at->format('d.m.Y H:i'));

            $row++;
        }

        $sheet->getStyle('A2:E' . $row-1)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_HAIR);


        // Результат
        $writer = new Xlsx($spreadsheet);
        $exportFileName = sprintf(
            'обращения_за_%s-%s.xlsx',
            $from->format('d.m.Y'),
            $to->format('d.m.Y')
        );
        $response = response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $exportFileName);

        unset($writer);
        unset($spreadsheet);

        return $response;
    }
}
