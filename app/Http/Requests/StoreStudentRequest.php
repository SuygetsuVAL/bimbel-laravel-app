<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6',
            'phone'        => 'nullable|string|max:20',
            'nis'          => 'required|unique:students,nis',
            'class_id'     => 'required|exists:classes,id',
            'parent_name'  => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'parent_email' => 'nullable|email|max:255',
            'address'      => 'nullable|string|max:1000',
            'birth_date'   => 'nullable|date|before:today',
            'join_date'    => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama siswa wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah digunakan akun lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'nis.required'      => 'NIS wajib diisi.',
            'nis.unique'        => 'NIS sudah terdaftar oleh siswa lain.',
            'class_id.required' => 'Kelas wajib dipilih.',
            'class_id.exists'   => 'Kelas yang dipilih tidak ditemukan.',
            'parent_email.email'=> 'Format email orang tua tidak valid.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
        ];
    }
}
