<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model AppNotification — menggunakan nama 'AppNotification' untuk
 * tidak bentrok dengan namespace Illuminate\Notifications\Notification.
 * Tabel: app_notifications.
 */
class AppNotification extends Model
{
    use HasFactory;

    protected $table = 'app_notifications';

    protected $fillable = [
        'title',
        'message',
        'target_role',
        'target_id',
        'is_read',
        'sent_by',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    // ── Scope ─────────────────────────────────────────────────────────────────

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForRole($query, string $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->where('target_role', 'all')
              ->orWhere('target_role', $role);
        });
    }
}
