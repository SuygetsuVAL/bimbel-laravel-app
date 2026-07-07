<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GradesExport
{
    public function __construct(private Collection $grades) {}

    public function download(string $filename): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Nilai Siswa');

        // ── Header ────────────────────────────────────────────────────────────
        $headers = ['No', 'Nama Siswa', 'NIS', 'Kelas', 'Jenis Ujian', 'Nilai', 'Nilai Maks', 'Persentase', 'Tanggal', 'Catatan'];
        $col     = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue("{$col}1", $header);
            $col++;
        }

        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Data ──────────────────────────────────────────────────────────────
        $row = 2;
        foreach ($this->grades as $i => $grade) {
            $pct = $grade->max_score > 0
                ? round(($grade->score / $grade->max_score) * 100, 1) . '%'
                : '-';

            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $grade->student?->user?->name ?? '-');
            $sheet->setCellValue("C{$row}", $grade->student?->nis ?? '-');
            $sheet->setCellValue("D{$row}", $grade->classRoom?->name ?? '-');
            $sheet->setCellValue("E{$row}", $grade->exam_type);
            $sheet->setCellValue("F{$row}", $grade->score);
            $sheet->setCellValue("G{$row}", $grade->max_score);
            $sheet->setCellValue("H{$row}", $pct);
            $sheet->setCellValue("I{$row}", $grade->graded_at?->format('d/m/Y') ?? '-');
            $sheet->setCellValue("J{$row}", $grade->notes ?? '-');
            $row++;
        }

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
