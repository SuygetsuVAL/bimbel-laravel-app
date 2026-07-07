<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nis',
        'class_id',
        'parent_name',
        'parent_phone',
        'parent_email',
        'address',
        'birth_date',
        'join_date',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date'  => 'date',
        'is_active'  => 'boolean',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // ── Accessor ─────────────────────────────────────────────────────────────

    public function getNameAttribute(): string
    {
        return $this->user?->name ?? '-';
    }
}
