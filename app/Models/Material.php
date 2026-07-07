<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'tutor_id',
        'title',
        'description',
        'file_path',
        'file_type',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    // ── Accessor ─────────────────────────────────────────────────────────────

    /**
     * URL file yang bisa diakses publik (storage link harus sudah dijalankan).
     */
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }
        return asset('storage/' . $this->file_path);
    }
}
