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
        $query = $division->subscribes()
            ->whereHasAccess()
            ->orderBy('start_at')
            ->where(function($query) use ($request){
                if ($request->has('from')) {
                    $query->where('start_at', '>=', Carbon::parse($request->input('from')));

                    if (!$request->has('to') && Carbon::parse($request->input('from'))->day === 1) {
                        $query->where('start_at', '<=', Carbon::parse($request->input('from'))->endOfMonth());
                    }
                }
                else
                    $query->where('start_at', '>=', now()->startOfMonth()->startOfDay());

                if ($request->has('to'))
                    $query->where('start_at', '<=', Carbon::parse($request->input('to')));
            });

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
        $response = response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        },
            'обращения_за_' .
            Carbon::parse($request->input('from'))->format('d.m.Y+H.i') .
            ($request->has('to') ? '-' . Carbon::parse($request->input('to'))->format('d.m.Y+H.i') : '') .
            '.xlsx'
        );

        unset($writer);
        unset($spreadsheet);

        return $response;
    }
}
