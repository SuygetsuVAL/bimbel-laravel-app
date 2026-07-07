<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $tutor = auth()->user()->tutor;

        $query = Attendance::with(['student.user', 'schedule.classRoom'])
            ->whereHas(
                'schedule.classRoom',
                fn($q) => $q->where('tutor_id', $tutor->id)
            )
            ->orderByDesc('date');

        // Filter tanggal
        if ($date = $request->input('date')) {
            $query->whereDate('date', $date);
        }

        // Filter kelas
        if ($classId = $request->input('class_id')) {
            $query->whereHas('schedule', fn($q) => $q->where('class_id', $classId));
        }

        $attendances = $query->paginate(30)->withQueryString();

        // Daftar kelas milik tutor ini untuk filter dropdown
        $classes = \App\Models\ClassRoom::where('tutor_id', $tutor->id)
            ->where('is_active', true)
            ->with('subject')
            ->get();

        return view('tutor.attendance.index', compact('attendances', 'classes'));
    }

    public function create($schedule_id)
    {
        $tutor    = auth()->user()->tutor;
        $schedule = Schedule::with(['classRoom.subject'])->findOrFail($schedule_id);

        // Pastikan schedule ini milik tutor yang login
        abort_unless($schedule->classRoom->tutor_id === $tutor->id, 403, 'Anda tidak memiliki akses ke jadwal ini.');

        // Cek apakah kehadiran untuk hari ini sudah diinput
        $alreadyRecorded = Attendance::where('schedule_id', $schedule_id)
            ->whereDate('date', today())
            ->exists();

        if ($alreadyRecorded) {
            return redirect()->route('tutor.attendance.index')
                ->with('error', 'Kehadiran untuk jadwal ini hari ini sudah diinput.');
        }

        // Siswa di kelas ini
        $students = Student::where('class_id', $schedule->class_id)
            ->where('is_active', true)
            ->with('user')
            ->orderBy('nis')
            ->get();

        return view('tutor.attendance.create', compact('schedule', 'students'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        $tutor      = auth()->user()->tutor;
        $scheduleId = $request->schedule_id;
        $date       = $request->date;

        $records = [];
        foreach ($request->attendance as $studentId => $status) {
            $records[] = [
                'student_id'  => $studentId,
                'schedule_id' => $scheduleId,
                'date'        => $date,
                'status'      => $status,
                'recorded_by' => auth()->id(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        // Insert bulk, skip duplikat jika ada
        Attendance::upsert(
            $records,
            ['student_id', 'schedule_id', 'date'],
            ['status', 'recorded_by', 'updated_at']
        );

        return redirect()->route('tutor.attendance.index')
            ->with('success', 'Kehadiran berhasil disimpan untuk ' . count($records) . ' siswa.');
    }
}
