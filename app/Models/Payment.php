<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'period',
        'amount',
        'method',
        'status',
        'paid_at',
        'verified_by',
        'notes',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ── Scope ─────────────────────────────────────────────────────────────────

    public function scopeUnpaid($query)
    {
        return $query->where('status', 'belum_lunas');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopeByPeriod($query, string $period)
    {
        return $query->where('period', $period);
    }

    // ── Accessor ─────────────────────────────────────────────────────────────

    public function getAmountFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getPeriodFormattedAttribute(): string
    {
        // '2026-06' → 'Juni 2026'
        $bulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April',   '05' => 'Mei',       '06' => 'Juni',
            '07' => 'Juli',    '08' => 'Agustus',   '09' => 'September',
            '10' => 'Oktober', '11' => 'November',  '12' => 'Desember',
        ];
        [$year, $month] = explode('-', $this->period);
        return ($bulan[$month] ?? $month) . ' ' . $year;
    }
}
