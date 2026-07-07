<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'schedule_id'    => 'required|exists:schedules,id',
            'date'           => 'required|date|before_or_equal:today',
            'attendance'     => 'required|array|min:1',
            'attendance.*'   => 'required|in:hadir,sakit,izin,alpha',
        ];
    }

    public function messages(): array
    {
        return [
            'schedule_id.required'  => 'Jadwal wajib dipilih.',
            'schedule_id.exists'    => 'Jadwal tidak ditemukan.',
            'date.required'         => 'Tanggal wajib diisi.',
            'date.before_or_equal'  => 'Tanggal tidak boleh melebihi hari ini.',
            'attendance.required'   => 'Data kehadiran wajib diisi.',
            'attendance.array'      => 'Format data kehadiran tidak valid.',
            'attendance.min'        => 'Minimal satu siswa harus dicatat kehadirannya.',
            'attendance.*.required' => 'Status kehadiran setiap siswa wajib dipilih.',
            'attendance.*.in'       => 'Status kehadiran tidak valid. Pilih: hadir, sakit, izin, atau alpha.',
        ];
    }
}
