@extends('layouts.tutor')
@section('title', 'Manajemen Materi')

@section('content')
<div class="max-w-[1160px] mx-auto space-y-6">
    <!-- Page Header & Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
        <div>
            <h2 class="font-headline-lg text-gray-900 font-bold text-2xl">Manajemen Materi</h2>
            <p class="text-gray-500 mt-1">Kelola dan unggah materi pembelajaran Anda untuk siswa.</p>
        </div>
        <a href="{{ route('tutor.materials.create') }}" class="flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-lg font-semibold text-xs hover:bg-primary-dark transition-all shadow-md active:scale-95">
            <span class="material-symbols-outlined text-[18px]">upload</span>
            Upload Materi Baru
        </a>
    </div>

    <!-- Bento Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-tint rounded-lg flex items-center justify-center text-primary">
                <span class="material-symbols-outlined">description</span>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-tight font-semibold">Total Materi Anda</p>
                <h3 class="text-xl font-bold text-gray-900">{{ $materials->total() }} File</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                <span class="material-symbols-outlined">school</span>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-tight font-semibold">Kelas Terhubung</p>
                <h3 class="text-xl font-bold text-gray-900">{{ \App\Models\ClassRoom::where('tutor_id', auth()->user()->tutor->id)->count() }} Kelas</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center text-success">
                <span class="material-symbols-outlined">download</span>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-tight font-semibold">Status Sistem</p>
                <h3 class="text-xl font-bold text-gray-900 text-success">Aktif & Stabil</h3>
            </div>
        </div>
    </div>

    <!-- Content Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h4 class="font-bold text-gray-900 text-sm uppercase tracking-wider text-primary">Daftar Materi</h4>
            
            <form action="{{ route('tutor.materials.index') }}" method="GET" class="flex gap-2">
                <select name="class_id" onchange="this.form.submit()" class="border-gray-300 rounded-lg text-xs py-1.5 focus:ring-primary focus:border-primary">
                    <option value="">Semua Kelas</option>
                    @foreach(\App\Models\ClassRoom::where('tutor_id', auth()->user()->tutor->id)->get() as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold text-gray-700 text-sm">Judul Materi</th>
                        <th class="px-6 py-4 font-semibold text-gray-700 text-sm">Kelas</th>
                        <th class="px-6 py-4 font-semibold text-gray-700 text-sm">Jenis File</th>
                        <th class="px-6 py-4 font-semibold text-gray-700 text-sm">Catatan</th>
                        <th class="px-6 py-4 font-semibold text-gray-700 text-center text-sm w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($materials as $m)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-red-50 text-red-500 rounded">
                                    <span class="material-symbols-outlined">{{ $m->file_type == 'pdf' ? 'picture_as_pdf' : 'description' }}</span>
                                </div>
                                <div>
                                    @if($m->file_path)
                                        <a href="{{ $m->file_url }}" target="_blank" class="font-bold text-gray-900 hover:text-primary hover:underline text-sm flex items-center gap-1">
                                            {{ $m->title }} <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                                        </a>
                                    @else
                                        <span class="font-bold text-gray-900 text-sm">{{ $m->title }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600 font-medium">{{ $m->classRoom->name }}</td>
                        <td class="px-6 py-4 text-xs text-gray-600 uppercase font-bold">{{ $m->file_type }}</td>
                        <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">{{ $m->description ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('tutor.materials.edit', $m->id) }}" class="p-2 text-gray-400 hover:text-primary hover:bg-primary-tint rounded-lg transition-all" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </a>
                                <form action="{{ route('tutor.materials.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-error hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <span class="material-symbols-outlined text-4xl mb-2">folder_open</span>
                            <p class="font-medium text-gray-600">Belum ada materi pelajaran yang diupload.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-gray-100 bg-gray-50/30">
            {{ $materials->links() }}
        </div>
    </div>
</div>
@endsection
