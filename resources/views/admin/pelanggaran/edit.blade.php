@extends('layout')

@section('content')
<h2 class="text-xl font-bold text-center mb-4">Edit Pelanggaran</h2>

<form action="{{ route('pelanggaran.update', $pelanggaran->id) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    {{-- NIS (Readonly) --}}
    <div class="grid gap-2">
        <label>NIS:</label>
        <input type="text" name="nis" value="{{ $pelanggaran->nis }}" required readonly
            class="border p-2 rounded w-full bg-gray-100 dark:bg-gray-700 dark:text-white">
        @error('nis')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Kategori --}}
    <div class="grid gap-2">
        <label>Kategori Pelanggaran:</label>
        <div class="flex gap-4">
            <label>
                <input type="radio" name="kategori" value="ringan" {{ $pelanggaran->kategori == 'ringan' ? 'checked' : '' }}> Ringan
            </label>
            <label>
                <input type="radio" name="kategori" value="sedang" {{ $pelanggaran->kategori == 'sedang' ? 'checked' : '' }}> Sedang
            </label>
            <label>
                <input type="radio" name="kategori" value="berat" {{ $pelanggaran->kategori == 'berat' ? 'checked' : '' }}> Berat
            </label>
        </div>
        @error('kategori')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Keterangan --}}
    <div class="grid gap-2">
        <label>Keterangan</label>
        <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan', $pelanggaran->keterangan) }}"
            class="border p-2 rounded w-full bg-gray-100 dark:bg-gray-700 dark:text-white">
        @error('keterangan')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tindakan --}}
    <div class="grid gap-2">
        <label>Tindakan</label>
        <input type="text" name="tindakan" id="tindakan" value="{{ old('tindakan', $pelanggaran->tindakan) }}"
            class="border p-2 rounded w-full bg-gray-100 dark:bg-gray-700 dark:text-white">
        @error('tindakan')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tombol Submit --}}
    <button type="submit"
        class="w-full bg-blue-500 hover:bg-blue-600 text-white p-2 rounded transition-transform hover:scale-105">
        Update
    </button>
</form>
@endsection
