@extends('layout')

@section('content')
<h2 class="text-xl font-bold text-center mb-4">Tambah Pelanggaran</h2>

{{-- Tampilkan semua error di atas --}}
@if ($errors->any())
<div class="bg-red-100 text-red-700 border border-red-400 p-4 rounded mb-4">
    <strong class="font-bold">Ups!</strong>
    <ul class="mt-2 list-disc list-inside text-sm">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('pelanggaran.simpan') }}" method="POST" class="space-y-4">
    @csrf

    <div class="grid gap-2">
        <label>NIS:</label>
        <input type="text" name="nis" value="{{ request('nis') }}" readonly
            class="border p-2 rounded w-full bg-gray-100 dark:bg-gray-700 dark:text-white">
        @error('nis')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-2">
        <label>Kategori Pelanggaran:</label>
        <div class="flex gap-4">
            <label>
                <input type="radio" name="kategori" value="ringan" {{ old('kategori') == 'ringan' ? 'checked' : '' }} required> Ringan
            </label>
            <label>
                <input type="radio" name="kategori" value="sedang" {{ old('kategori') == 'sedang' ? 'checked' : '' }} required> Sedang
            </label>
            <label>
                <input type="radio" name="kategori" value="berat" {{ old('kategori') == 'berat' ? 'checked' : '' }} required> Berat
            </label>
        </div>
        @error('kategori')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-2">
        <label>Keterangan</label>
        <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan') }}"
            class="border p-2 rounded w-full bg-gray-100 dark:bg-gray-700 dark:text-white">
        @error('keterangan')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-2">
        <label>Tindakan</label>
        <input type="text" name="tindakan" id="tindakan" value="{{ old('tindakan') }}"
            class="border p-2 rounded w-full bg-gray-100 dark:bg-gray-700 dark:text-white">
        @error('tindakan')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit"
        class="w-full bg-green-500 hover:bg-green-600 text-white p-2 rounded transition-transform hover:scale-105">
        Simpan
    </button>
</form>
@endsection
