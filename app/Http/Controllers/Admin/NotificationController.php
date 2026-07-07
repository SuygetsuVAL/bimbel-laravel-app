<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = AppNotification::with('sender')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'message'     => 'required|string',
            'target_role' => 'required|in:all,admin,tutor,student,parent',
        ], [
            'title.required'       => 'Judul pengumuman wajib diisi.',
            'message.required'     => 'Isi pengumuman wajib diisi.',
            'target_role.required' => 'Target penerima wajib dipilih.',
            'target_role.in'       => 'Target penerima tidak valid.',
        ]);

        AppNotification::create([
            'title'       => $request->title,
            'message'     => $request->message,
            'target_role' => $request->target_role,
            'target_id'   => null,
            'is_read'     => false,
            'sent_by'     => auth()->id(),
        ]);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Pengumuman berhasil dikirim.');
    }

    public function destroy(AppNotification $notification)
    {
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
