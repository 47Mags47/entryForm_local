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
            'to' => ['nullable', 'date_format:Y-m-d'],
            'worker_ids' => ['nullable', 'array'],
            'worker_ids.*' => ['integer'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['integer'],
            'search' => ['nullable', 'string'],
        ]);

        $from = Carbon::parse($request->input('from', now()->startOfMonth()->toDateString()))->startOfDay();
        $to = Carbon::parse($request->input('to', now()->endOfMonth()->toDateString()))->endOfDay();

        $query = $division->subscribes()
            ->whereHasAccess()
            ->whereBetween('start_at', [$from, $to])
            ->when(
                $request->filled('worker_ids'),
                fn ($query) => $query->whereIn('worker_id', $request->input('worker_ids'))
            )
            ->when(
                $request->filled('service_ids'),
                fn ($query) => $query->whereIn('service_id', $request->input('service_ids'))
            )
            ->when($request->input('search'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%");
                });
            })
            ->orderBy('start_at');

        $spreadsheet = IOFactory::load(
            storage_path('app/private/templates/subscribes/subscribesIndex.xlsx')
        );

        $appealsSheet = $spreadsheet->getSheetByName('Обращения');
        $notesSheet = $spreadsheet->getSheetByName('Заметки');

        if (!$appealsSheet || !$notesSheet) {
            abort(500, 'В шаблоне отсутствуют листы «Обращения» или «Заметки».');
        }

        // Обращения
        $subscribeRows = [];
        $row = 2;

        foreach ((clone $query)->with(['service', 'worker'])->lazy() as $subscribe) {
            $subscribeRows[$subscribe->id] = $row;

            $appealsSheet->setCellValue("A{$row}", $subscribe->id);

            $appealsSheet->setCellValue(
                "B{$row}",
                trim("{$subscribe->last_name} {$subscribe->first_name} {$subscribe->middle_name}")
            );

            $appealsSheet->setCellValue(
                "C{$row}",
                $subscribe->service?->name
            );

            $appealsSheet->setCellValue(
                "D{$row}",
                trim(
                    ($subscribe->worker?->last_name ?? '') . ' ' .
                    ($subscribe->worker?->first_name ?? '') . ' ' .
                    ($subscribe->worker?->middle_name ?? '')
                )
            );

            $appealsSheet->setCellValue(
                "E{$row}",
                $subscribe->worker?->office
            );

            $appealsSheet->setCellValue(
                "F{$row}",
                $subscribe->start_at?->format('d.m.Y H:i')
            );

            $row++;
        }

        if ($row > 2) {
            $appealsSheet->getStyle("A2:F" . ($row - 1))
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_HAIR);
        }

        // Заметки
        $row = 2;

        foreach ((clone $query)->with('notes.worker')->lazy() as $subscribe) {
            foreach ($subscribe->notes as $note) {
                $notesSheet->setCellValue("A{$row}", $note->id);
                $notesSheet->setCellValue("B{$row}", $subscribe->id);

                // Ссылка на строку обращения, а не на ID записи в БД
                if (isset($subscribeRows[$subscribe->id])) {
                    $targetRow = $subscribeRows[$subscribe->id];

                    $notesSheet->getCell("B{$row}")
                        ->getHyperlink()
                        ->setUrl("sheet://'Обращения'!A{$targetRow}");
                }

                $notesSheet->setCellValue(
                    "C{$row}",
                    trim(
                        ($note->worker?->last_name ?? '') . ' ' .
                        ($note->worker?->first_name ?? '') . ' ' .
                        ($note->worker?->middle_name ?? '')
                    )
                );

                $notesSheet->setCellValue("D{$row}", $note->note);

                $row++;
            }
        }

        if ($row > 2) {
            $notesSheet->getStyle("A2:D" . ($row - 1))
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_HAIR);
        }

        $writer = new Xlsx($spreadsheet);

        $exportFileName = sprintf(
            'обращения_за_%s-%s.xlsx',
            $from->format('d.m.Y'),
            $to->format('d.m.Y')
        );

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $exportFileName);
    }
}
