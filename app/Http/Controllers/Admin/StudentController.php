<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['user', 'classRoom.subject'])
            ->where('is_active', true);

        // Filter pencarian
        if ($search = $request->input('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhere('nis', 'like', "%{$search}%");
        }

        // Filter kelas
        if ($classId = $request->input('class_id')) {
            $query->where('class_id', $classId);
        }

        $students = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $classes  = ClassRoom::where('is_active', true)->with('subject')->get();

        return view('admin.students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = ClassRoom::where('is_active', true)->with('subject')->get();
        return view('admin.students.create', compact('classes'));
    }

    public function store(StoreStudentRequest $request)
    {
        DB::transaction(function () use ($request) {
            // 1. Buat User
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'phone'     => $request->phone,
                'is_active' => true,
            ]);

            // 2. Buat Student
            Student::create([
                'user_id'      => $user->id,
                'nis'          => $request->nis,
                'class_id'     => $request->class_id,
                'parent_name'  => $request->parent_name,
                'parent_phone' => $request->parent_phone,
                'parent_email' => $request->parent_email,
                'address'      => $request->address,
                'birth_date'   => $request->birth_date,
                'join_date'    => $request->join_date ?? now()->toDateString(),
                'is_active'    => true,
            ]);
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        $classes = ClassRoom::where('is_active', true)->with('subject')->get();
        $student->load('user');
        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $student->user_id,
            'nis'          => 'required|unique:students,nis,' . $student->id,
            'class_id'     => 'required|exists:classes,id',
            'parent_name'  => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'birth_date'   => 'nullable|date',
        ], [
            'name.required'  => 'Nama siswa wajib diisi.',
            'email.unique'   => 'Email sudah digunakan akun lain.',
            'nis.unique'     => 'NIS sudah terdaftar.',
            'class_id.exists' => 'Kelas tidak ditemukan.',
        ]);

        DB::transaction(function () use ($request, $student) {
            // Update User
            $student->user->update([
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            // Update password jika diisi
            if ($request->filled('password')) {
                $request->validate(['password' => 'min:6']);
                $student->user->update(['password' => Hash::make($request->password)]);
            }

            // Update Student
            $student->update([
                'nis'          => $request->nis,
                'class_id'     => $request->class_id,
                'parent_name'  => $request->parent_name,
                'parent_phone' => $request->parent_phone,
                'parent_email' => $request->parent_email,
                'address'      => $request->address,
                'birth_date'   => $request->birth_date,
            ]);
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        // Soft delete: nonaktifkan, tidak benar-benar hapus
        $student->update(['is_active' => false]);
        $student->user->update(['is_active' => false]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Siswa berhasil dinonaktifkan.');
    }
}
