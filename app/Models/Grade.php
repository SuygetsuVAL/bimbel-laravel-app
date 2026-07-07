<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'exam_type',
        'score',
        'max_score',
        'notes',
        'graded_by',
        'graded_at',
    ];

    protected $casts = [
        'score'      => 'decimal:2',
        'max_score'  => 'decimal:2',
        'graded_at'  => 'date',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    // ── Accessor ─────────────────────────────────────────────────────────────

    /**
     * Nilai dalam persentase (0-100).
     */
    public function getScorePercentAttribute(): float
    {
        if (!$this->max_score || $this->max_score == 0) {
            return 0;
        }
        return round(($this->score / $this->max_score) * 100, 2);
    }
}
