<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tutor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'specialization',
        'education',
        'join_date',
        'is_active',
    ];

    protected $casts = [
        'join_date' => 'date',
        'is_active' => 'boolean',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function recordedAttendances()
    {
        return $this->hasMany(Attendance::class, 'recorded_by', 'user_id');
    }

    // ── Accessor ─────────────────────────────────────────────────────────────

    public function getNameAttribute(): string
    {
        return $this->user?->name ?? '-';
    }
}
