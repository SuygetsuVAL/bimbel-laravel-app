<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student.user', 'student.classRoom', 'verifiedBy'])
            ->orderByDesc('created_at');

        // Filter status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter periode (YYYY-MM)
        if ($period = $request->input('period')) {
            $query->byPeriod($period);
        }

        // Filter pencarian nama siswa
        if ($search = $request->input('search')) {
            $query->whereHas('student.user', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $payments = $query->paginate(20)->withQueryString();

        // Ringkasan untuk periode yang dipilih
        $currentPeriod = $request->input('period', now()->format('Y-m'));
        $summary = [
            'total_lunas'       => Payment::byPeriod($currentPeriod)->paid()->sum('amount'),
            'total_belum_lunas' => Payment::byPeriod($currentPeriod)->unpaid()->count(),
        ];

        return view('admin.payments.index', compact('payments', 'summary', 'currentPeriod'));
    }

    public function verify(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'method' => 'required|in:bca,mandiri,gopay,ovo,tunai',
            'notes'  => 'nullable|string|max:500',
        ], [
            'method.required' => 'Metode pembayaran wajib dipilih.',
            'method.in'       => 'Metode pembayaran tidak valid.',
        ]);

        $payment->update([
            'status'      => 'lunas',
            'method'      => $request->method,
            'paid_at'     => now(),
            'verified_by' => auth()->id(),
            'notes'       => $request->notes,
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', "Pembayaran {$payment->student->user->name} bulan {$payment->period_formatted} berhasil diverifikasi.");
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'period'     => 'required|date_format:Y-m',
            'amount'     => 'required|numeric|min:1000',
        ], [
            'student_id.required' => 'Siswa wajib dipilih.',
            'period.required'     => 'Periode wajib diisi (format: YYYY-MM).',
            'amount.required'     => 'Jumlah tagihan wajib diisi.',
        ]);

        Payment::firstOrCreate(
            ['student_id' => $request->student_id, 'period' => $request->period],
            ['amount' => $request->amount, 'status' => 'belum_lunas']
        );

        return redirect()->route('admin.payments.index')
            ->with('success', 'Tagihan berhasil dibuat.');
    }
}
