<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\ClassRoom;
use App\Exports\AttendanceExport;
use App\Exports\GradesExport;
use App\Exports\PaymentsExport;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::where('is_active', true)->with('subject')->get();

        return view('admin.reports.index', compact('classes'));
    }

    public function exportAttendance(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'date_start' => 'required|date',
            'date_end'   => 'required|date|after_or_equal:date_start',
        ], [
            'class_id.required'   => 'Kelas wajib dipilih.',
            'date_start.required' => 'Tanggal mulai wajib diisi.',
            'date_end.required'   => 'Tanggal selesai wajib diisi.',
            'date_end.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ]);

        $attendances = Attendance::with(['student.user', 'schedule.classRoom'])
            ->whereHas('schedule', fn($q) => $q->where('class_id', $request->class_id))
            ->whereBetween('date', [$request->date_start, $request->date_end])
            ->orderBy('date')
            ->get();

        $export = new AttendanceExport($attendances);
        return $export->download("kehadiran_{$request->date_start}_{$request->date_end}.xlsx");
    }

    public function exportGrades(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $grades = Grade::with(['student.user', 'classRoom'])
            ->where('class_id', $request->class_id)
            ->orderBy('student_id')
            ->orderBy('exam_type')
            ->get();

        $export = new GradesExport($grades);
        return $export->download("nilai_kelas_{$request->class_id}.xlsx");
    }

    public function exportPayments(Request $request)
    {
        $request->validate([
            'period' => 'required|date_format:Y-m',
        ], [
            'period.required'     => 'Periode wajib diisi.',
            'period.date_format'  => 'Format periode harus YYYY-MM.',
        ]);

        $payments = Payment::with(['student.user', 'student.classRoom', 'verifiedBy'])
            ->byPeriod($request->period)
            ->orderBy('student_id')
            ->get();

        $export = new PaymentsExport($payments);
        return $export->download("pembayaran_{$request->period}.xlsx");
    }
}
