<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['classRoom.subject', 'classRoom.tutor.user'])
            ->where('is_active', true);

        if ($classId = $request->input('class_id')) {
            $query->where('class_id', $classId);
        }

        if ($day = $request->input('day_of_week')) {
            $query->where('day_of_week', $day);
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->paginate(20)->withQueryString();
        $classes   = ClassRoom::where('is_active', true)->with('subject')->get();

        return view('admin.schedules.index', compact('schedules', 'classes'));
    }

    public function create()
    {
        $classes = ClassRoom::where('is_active', true)->with('subject')->get();

        return view('admin.schedules.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id'    => 'required|exists:classes,id',
            'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'room'        => 'nullable|string|max:100',
        ], [
            'class_id.required'    => 'Kelas wajib dipilih.',
            'class_id.exists'      => 'Kelas tidak ditemukan.',
            'day_of_week.required' => 'Hari wajib dipilih.',
            'day_of_week.in'       => 'Hari tidak valid.',
            'start_time.required'  => 'Jam mulai wajib diisi.',
            'end_time.required'    => 'Jam selesai wajib diisi.',
            'end_time.after'       => 'Jam selesai harus setelah jam mulai.',
        ]);

        Schedule::create([
            'class_id'    => $request->class_id,
            'day_of_week' => $request->day_of_week,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'room'        => $request->room,
            'is_active'   => true,
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule)
    {
        $classes = ClassRoom::where('is_active', true)->with('subject')->get();

        return view('admin.schedules.edit', compact('schedule', 'classes'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'class_id'    => 'required|exists:classes,id',
            'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'room'        => 'nullable|string|max:100',
        ]);

        $schedule->update($request->only(['class_id', 'day_of_week', 'start_time', 'end_time', 'room']));

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->update(['is_active' => false]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dinonaktifkan.');
    }
}
