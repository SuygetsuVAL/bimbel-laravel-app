<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $tutor = auth()->user()->tutor;

        $query = Grade::with(['student.user', 'classRoom'])
            ->whereHas('classRoom', fn($q) => $q->where('tutor_id', $tutor->id))
            ->orderBy('class_id')
            ->orderBy('student_id');

        // Filter kelas
        if ($classId = $request->input('class_id')) {
            $query->where('class_id', $classId);
        }

        // Filter jenis ujian
        if ($examType = $request->input('exam_type')) {
            $query->where('exam_type', $examType);
        }

        $grades  = $query->paginate(30)->withQueryString();
        $classes = ClassRoom::where('tutor_id', $tutor->id)
            ->where('is_active', true)
            ->with('subject')
            ->get();

        return view('tutor.grades.index', compact('grades', 'classes'));
    }

    public function create()
    {
        $tutor = auth()->user()->tutor;

        $classes = ClassRoom::where('tutor_id', $tutor->id)
            ->where('is_active', true)
            ->with('subject')
            ->get();

        return view('tutor.grades.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $tutor = auth()->user()->tutor;

        $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'student_id' => 'required|exists:students,id',
            'exam_type'  => 'required|in:UH,UTS,UAS,Tugas,Quiz',
            'score'      => 'required|numeric|min:0|max:100',
            'max_score'  => 'nullable|numeric|min:1|max:1000',
            'notes'      => 'nullable|string|max:500',
            'graded_at'  => 'nullable|date',
        ], [
            'class_id.required'   => 'Kelas wajib dipilih.',
            'student_id.required' => 'Siswa wajib dipilih.',
            'exam_type.required'  => 'Jenis ujian wajib dipilih.',
            'exam_type.in'        => 'Jenis ujian tidak valid.',
            'score.required'      => 'Nilai wajib diisi.',
            'score.max'           => 'Nilai tidak boleh lebih dari 100.',
        ]);

        // Pastikan kelas ini milik tutor yang login
        $classRoom = ClassRoom::findOrFail($request->class_id);
        abort_unless($classRoom->tutor_id === $tutor->id, 403, 'Anda tidak dapat menginput nilai untuk kelas ini.');

        Grade::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'class_id'   => $request->class_id,
                'exam_type'  => $request->exam_type,
            ],
            [
                'score'      => $request->score,
                'max_score'  => $request->max_score ?? 100,
                'notes'      => $request->notes,
                'graded_by'  => auth()->id(),
                'graded_at'  => $request->graded_at ?? today(),
            ]
        );

        return redirect()->route('tutor.grades.index')
            ->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * API endpoint: ambil siswa berdasarkan class_id (digunakan oleh JS pada form input nilai)
     */
    public function getStudentsByClass(Request $request)
    {
        $tutor    = auth()->user()->tutor;
        $classId  = $request->input('class_id');

        $classRoom = ClassRoom::where('id', $classId)
            ->where('tutor_id', $tutor->id)
            ->firstOrFail();

        $students = Student::where('class_id', $classId)
            ->where('is_active', true)
            ->with('user')
            ->get()
            ->map(fn($s) => ['id' => $s->id, 'name' => $s->user->name, 'nis' => $s->nis]);

        return response()->json($students);
    }
}
