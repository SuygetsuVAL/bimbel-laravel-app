<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TutorController extends Controller
{
    public function index(Request $request)
    {
        $query = Tutor::with('user')->where('is_active', true);

        if ($search = $request->input('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhere('nip', 'like', "%{$search}%");
        }

        $tutors = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        return view('admin.tutors.index', compact('tutors'));
    }

    public function create()
    {
        return view('admin.tutors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:6',
            'phone'          => 'nullable|string|max:20',
            'nip'            => 'nullable|unique:tutors,nip',
            'specialization' => 'nullable|string|max:255',
            'education'      => 'nullable|string|max:255',
            'join_date'      => 'nullable|date',
        ], [
            'name.required'  => 'Nama tutor wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan.',
            'nip.unique'     => 'NIP sudah terdaftar.',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat User
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'phone'     => $request->phone,
                'is_active' => true,
            ]);

            // 2. Assign role tutor
            $user->assignRole('tutor');

            // 3. Buat Tutor
            Tutor::create([
                'user_id'        => $user->id,
                'nip'            => $request->nip,
                'specialization' => $request->specialization,
                'education'      => $request->education,
                'join_date'      => $request->join_date ?? now()->toDateString(),
                'is_active'      => true,
            ]);
        });

        return redirect()->route('admin.tutors.index')
            ->with('success', 'Tutor berhasil ditambahkan.');
    }

    public function edit(Tutor $tutor)
    {
        $tutor->load('user');
        return view('admin.tutors.edit', compact('tutor'));
    }

    public function update(Request $request, Tutor $tutor)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $tutor->user_id,
            'phone'          => 'nullable|string|max:20',
            'nip'            => 'nullable|unique:tutors,nip,' . $tutor->id,
            'specialization' => 'nullable|string|max:255',
            'education'      => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama tutor wajib diisi.',
            'email.unique'  => 'Email sudah digunakan akun lain.',
            'nip.unique'    => 'NIP sudah terdaftar.',
        ]);

        DB::transaction(function () use ($request, $tutor) {
            $tutor->user->update([
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            if ($request->filled('password')) {
                $request->validate(['password' => 'min:6']);
                $tutor->user->update(['password' => Hash::make($request->password)]);
            }

            $tutor->update([
                'nip'            => $request->nip,
                'specialization' => $request->specialization,
                'education'      => $request->education,
            ]);
        });

        return redirect()->route('admin.tutors.index')
            ->with('success', 'Data tutor berhasil diperbarui.');
    }

    public function destroy(Tutor $tutor)
    {
        $tutor->update(['is_active' => false]);
        $tutor->user->update(['is_active' => false]);

        return redirect()->route('admin.tutors.index')
            ->with('success', 'Tutor berhasil dinonaktifkan.');
    }
}
