<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_id'    => 'required|exists:classes,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,mp4,avi,mkv,jpg,jpeg,png|max:51200', // max 50MB
            'file_type'   => 'nullable|in:pdf,doc,video,link,other',
        ];
    }

    public function messages(): array
    {
        return [
            'class_id.required'  => 'Kelas wajib dipilih.',
            'class_id.exists'    => 'Kelas tidak ditemukan.',
            'title.required'     => 'Judul materi wajib diisi.',
            'title.max'          => 'Judul materi maksimal 255 karakter.',
            'description.max'    => 'Deskripsi maksimal 2000 karakter.',
            'file.file'          => 'File tidak valid.',
            'file.mimes'         => 'Format file tidak didukung. Gunakan: PDF, DOC, DOCX, MP4, AVI, MKV, JPG, PNG.',
            'file.max'           => 'Ukuran file maksimal 50MB.',
        ];
    }
}
