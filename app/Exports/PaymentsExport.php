<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentsExport
{
    public function __construct(private Collection $payments) {}

    public function download(string $filename): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pembayaran');

        // ── Header ────────────────────────────────────────────────────────────
        $headers = ['No', 'Nama Siswa', 'NIS', 'Kelas', 'Periode', 'Jumlah (Rp)', 'Metode', 'Status', 'Tgl Bayar', 'Verifikator', 'Catatan'];
        $col     = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue("{$col}1", $header);
            $col++;
        }

        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Data ──────────────────────────────────────────────────────────────
        $row = 2;
        foreach ($this->payments as $i => $pay) {
            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $pay->student?->user?->name ?? '-');
            $sheet->setCellValue("C{$row}", $pay->student?->nis ?? '-');
            $sheet->setCellValue("D{$row}", $pay->student?->classRoom?->name ?? '-');
            $sheet->setCellValue("E{$row}", $pay->period_formatted);
            $sheet->setCellValue("F{$row}", $pay->amount);
            $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("G{$row}", $pay->method ? strtoupper($pay->method) : '-');
            $sheet->setCellValue("H{$row}", match ($pay->status) {
                'lunas'       => 'LUNAS',
                'belum_lunas' => 'BELUM LUNAS',
                'cicilan'     => 'CICILAN',
                default       => strtoupper($pay->status),
            });
            $sheet->setCellValue("I{$row}", $pay->paid_at?->format('d/m/Y H:i') ?? '-');
            $sheet->setCellValue("J{$row}", $pay->verifiedBy?->name ?? '-');
            $sheet->setCellValue("K{$row}", $pay->notes ?? '-');
            $row++;
        }

        // ── Total di baris terakhir ───────────────────────────────────────────
        $sheet->setCellValue("A{$row}", 'TOTAL');
        $sheet->setCellValue("F{$row}", $this->payments->sum('amount'));
        $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("A{$row}:K{$row}")->getFont()->setBold(true);

        foreach (range('A', 'K') as $col) {
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
