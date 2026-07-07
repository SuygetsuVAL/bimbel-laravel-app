<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceExport
{
    public function __construct(private Collection $attendances) {}

    public function download(string $filename): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Kehadiran');

        // ── Header ────────────────────────────────────────────────────────────
        $headers = ['No', 'Tanggal', 'Nama Siswa', 'NIS', 'Kelas', 'Jadwal', 'Status', 'Catatan'];
        $col     = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue("{$col}1", $header);
            $col++;
        }

        // Style header
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Data ──────────────────────────────────────────────────────────────
        $row = 2;
        foreach ($this->attendances as $i => $att) {
            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $att->date?->format('d/m/Y') ?? '-');
            $sheet->setCellValue("C{$row}", $att->student?->user?->name ?? '-');
            $sheet->setCellValue("D{$row}", $att->student?->nis ?? '-');
            $sheet->setCellValue("E{$row}", $att->schedule?->classRoom?->name ?? '-');
            $sheet->setCellValue("F{$row}", ($att->schedule?->start_time ?? '') . ' - ' . ($att->schedule?->end_time ?? ''));
            $sheet->setCellValue("G{$row}", strtoupper($att->status));
            $sheet->setCellValue("H{$row}", $att->notes ?? '-');
            $row++;
        }

        // Auto size kolom
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $this->streamDownload($spreadsheet, $filename);
    }

    private function streamDownload(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
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
