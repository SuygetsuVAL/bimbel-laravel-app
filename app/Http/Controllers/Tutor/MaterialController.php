<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaterialRequest;
use App\Models\Material;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $tutor = auth()->user()->tutor;

        $materials = Material::with('classRoom.subject')
            ->where('tutor_id', $tutor->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('tutor.materials.index', compact('materials'));
    }

    public function create()
    {
        $tutor   = auth()->user()->tutor;
        $classes = ClassRoom::where('tutor_id', $tutor->id)
            ->where('is_active', true)
            ->with('subject')
            ->get();

        return view('tutor.materials.create', compact('classes'));
    }

    public function store(StoreMaterialRequest $request)
    {
        $tutor    = auth()->user()->tutor;
        $filePath = null;
        $fileType = $request->file_type ?? 'other';

        // Upload file jika ada
        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $ext      = $file->getClientOriginalExtension();
            $fileType = match (strtolower($ext)) {
                'pdf'            => 'pdf',
                'doc', 'docx'   => 'doc',
                'mp4', 'avi', 'mkv' => 'video',
                default          => 'other',
            };
            $filePath = $file->store("materials/{$tutor->id}", 'public');
        }

        Material::create([
            'class_id'     => $request->class_id,
            'tutor_id'     => $tutor->id,
            'title'        => $request->title,
            'description'  => $request->description,
            'file_path'    => $filePath,
            'file_type'    => $fileType,
            'is_published' => true,
        ]);

        return redirect()->route('tutor.materials.index')
            ->with('success', 'Materi berhasil diupload.');
    }

    public function edit(Material $material)
    {
        $tutor = auth()->user()->tutor;
        abort_unless($material->tutor_id === $tutor->id, 403, 'Anda tidak dapat mengedit materi ini.');

        $classes = ClassRoom::where('tutor_id', $tutor->id)
            ->where('is_active', true)
            ->with('subject')
            ->get();

        return view('tutor.materials.edit', compact('material', 'classes'));
    }

    public function update(Request $request, Material $material)
    {
        $tutor = auth()->user()->tutor;
        abort_unless($material->tutor_id === $tutor->id, 403);

        $request->validate([
            'title'    => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
        ]);

        $filePath = $material->file_path;

        if ($request->hasFile('file')) {
            // Hapus file lama
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file')->store("materials/{$tutor->id}", 'public');
        }

        $material->update([
            'title'       => $request->title,
            'class_id'    => $request->class_id,
            'description' => $request->description,
            'file_path'   => $filePath,
        ]);

        return redirect()->route('tutor.materials.index')
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        $tutor = auth()->user()->tutor;
        abort_unless($material->tutor_id === $tutor->id, 403);

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->route('tutor.materials.index')
            ->with('success', 'Materi berhasil dihapus.');
    }
}
