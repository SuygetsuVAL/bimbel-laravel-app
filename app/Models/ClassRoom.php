<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model ClassRoom — menggunakan nama 'ClassRoom' karena 'Class' adalah
 * keyword reserved di PHP. Tabel database tetap bernama 'classes'.
 */
class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'level',
        'subject_id',
        'tutor_id',
        'max_students',
        'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'max_students' => 'integer',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'class_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'class_id');
    }

    // ── Accessor ─────────────────────────────────────────────────────────────

    public function getStudentCountAttribute(): int
    {
        return $this->students()->count();
    }
}
