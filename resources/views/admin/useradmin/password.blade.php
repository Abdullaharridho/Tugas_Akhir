@extends('sideadmin')

@section('konten')
<div class="max-w-xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Ubah Password</h2>

    

    <form action="{{ route('useradmin.password.update') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Password Lama</label>
            <input type="password" name="current_password" class="w-full px-3 py-2 border rounded" required>
            @error('current_password') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Password Baru</label>
            <input type="password" name="new_password" class="w-full px-3 py-2 border rounded" required>
            @error('new_password') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" class="w-full px-3 py-2 border rounded" required>
        </div>

        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
@endsection
