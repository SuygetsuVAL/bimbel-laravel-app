<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Tutor;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassRoom::with(['subject', 'tutor.user'])
            ->where('is_active', true);

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('level', 'like', "%{$search}%");
        }

        $classes = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        $tutors   = Tutor::with('user')->where('is_active', true)->get();

        return view('admin.classes.create', compact('subjects', 'tutors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'level'        => 'nullable|string|max:50',
            'subject_id'   => 'required|exists:subjects,id',
            'tutor_id'     => 'nullable|exists:tutors,id',
            'max_students' => 'nullable|integer|min:1|max:100',
        ], [
            'name.required'       => 'Nama kelas wajib diisi.',
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'subject_id.exists'   => 'Mata pelajaran tidak ditemukan.',
        ]);

        ClassRoom::create([
            'name'         => $request->name,
            'level'        => $request->level,
            'subject_id'   => $request->subject_id,
            'tutor_id'     => $request->tutor_id,
            'max_students' => $request->max_students ?? 20,
            'is_active'    => true,
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(ClassRoom $class)
    {
        $subjects = Subject::orderBy('name')->get();
        $tutors   = Tutor::with('user')->where('is_active', true)->get();

        return view('admin.classes.edit', compact('class', 'subjects', 'tutors'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'level'        => 'nullable|string|max:50',
            'subject_id'   => 'required|exists:subjects,id',
            'tutor_id'     => 'nullable|exists:tutors,id',
            'max_students' => 'nullable|integer|min:1|max:100',
        ]);

        $class->update($request->only(['name', 'level', 'subject_id', 'tutor_id', 'max_students']));

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(ClassRoom $class)
    {
        $class->update(['is_active' => false]);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dinonaktifkan.');
    }
}
