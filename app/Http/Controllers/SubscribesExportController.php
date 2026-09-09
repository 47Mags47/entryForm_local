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
    public function index(Request $request, Division $division)
    {
        $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d']
        ]);

        $from = $request->filled('from')
            ? Carbon::parse($request->input('from'))->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $to = $request->filled('to')
            ? Carbon::parse($request->input('to'))->endOfDay()
            : now()->endOfMonth()->endOfDay();

        $query = $division->subscribes()
            ->whereHasAccess()
            ->orderBy('start_at')
            ->whereBetween('start_at', [$from, $to])
            ->when($request->input('worker_id'), function ($query, $workerId) {
                $query->where('worker_id', $workerId);
            })
            ->when($request->input('service_id'), function ($query, $serviceId) {
                $query->where('service_id', $serviceId);
            })
            ->when($request->input('search'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%");
                });
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
            $sheet->setCellValue("C{$row}", $subscribe->worker->last_name . ' ' . $subscribe->worker->first_name . ' ' . $subscribe->worker->middle_name);
            $sheet->setCellValue("D{$row}", $subscribe->worker->office);
            $sheet->setCellValue("E{$row}", $subscribe->start_at->format('d.m.Y H:i'));

            $row++;
        }

        $sheet->getStyle('A2:E' . $row - 1)
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
