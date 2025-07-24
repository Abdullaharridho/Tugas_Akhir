@php use Illuminate\Support\Str; @endphp
@extends('sideadmin')

@section('konten')

<div class="container mx-auto p-6"
    x-data="{
         openCreate: '{{ old('_from') === 'create' ? 'true' : 'false' }}' === 'true',
         openEdit: '{{ str_starts_with(old('_from'), 'edit_') ? Str::after(old('_from'), 'edit_') : 'null' }}'
     }">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">👨‍🏫 Daftar Guru</h1>
        <button @click="openCreate = true"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-5 rounded-full shadow transition">
            + Tambah Guru
        </button>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Nama</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Mapel</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($gurus as $guru)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-6 py-4 text-gray-800 dark:text-gray-200">{{ $guru->name }}</td>
                    <td class="px-6 py-4 text-gray-800 dark:text-gray-200">{{ $guru->email }}</td>
                    <td class="px-6 py-4 text-gray-800 dark:text-gray-200">
                        @foreach($guru->mapel as $mapel)
                        <span class="inline-block bg-gray-200 text-sm rounded-full px-3 py-1 mr-1 mb-1">
                            {{ $mapel->nama }}
                        </span>
                        @endforeach
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button @click="openEdit = '{{ $guru->id }}'"
                                class="flex items-center gap-1 bg-yellow-400 hover:bg-yellow-500 text-black font-medium py-1.5 px-4 rounded-full transition">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('guru.destroy', $guru->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button
                                    class="bg-red-600 hover:bg-red-700 text-white py-1 px-4 rounded-full transition">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- Modal Edit --}}
                <div x-show="openEdit === '{{ $guru->id }}'" x-cloak x-transition
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm">
                    <div class="bg-white dark:bg-gray-900 dark:text-white w-full max-w-md rounded-xl shadow-lg p-6 mx-4"
                        @click.away="openEdit = null">
                        <h2 class="text-xl font-bold mb-4">Edit Guru</h2>
                        <form action="{{ route('guru.update', $guru->id) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="_from" value="edit_{{ $guru->id }}">

                            @if ($errors->any() && old('_from') === 'edit_' . $guru->id)
                            <div
                                class="mb-4 text-sm text-red-600 bg-red-100 border border-red-400 rounded-lg p-3 overflow-auto max-h-32">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="mb-4">
                                <label class="block text-sm mb-1">Nama</label>
                                <input type="text" name="name" value="{{ old('name', $guru->name) }}"
                                    class="w-full rounded-lg px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $guru->email) }}"
                                    class="w-full rounded-lg px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm mb-1">Mapel</label>
                                <select name="mapel_ids[]" multiple
                                    class="w-full rounded-lg px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                                    @foreach ($mapels as $mapel)
                                    <option value="{{ $mapel->id }}"
                                        {{ in_array($mapel->id, old('mapel_ids', $guru->mapel->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $mapel->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex justify-end gap-2">
                                <button type="button" @click="openEdit = null"
                                    class="px-4 py-2 border rounded-full dark:border-gray-400">Batal</button>
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-full">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="4"
                        class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">Belum ada data guru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah --}}
    <div x-show="openCreate" x-cloak x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-900 dark:text-white w-full max-w-md rounded-xl shadow-lg p-6 mx-4"
            @click.away="openCreate = false">
            <h2 class="text-xl font-bold mb-4">Tambah Guru</h2>
            <form action="{{ route('guru.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_from" value="create">

                @if ($errors->any() && old('_from') === 'create')
                <div
                    class="mb-4 text-sm text-red-600 bg-red-100 border border-red-400 rounded-lg p-3 overflow-auto max-h-32">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mb-4">
                    <label class="block text-sm mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800"
                        required>
                    @error('name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800"
                        required>
                    @error('email')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm mb-1">Mapel</label>
                    <select name="mapel_ids[]" multiple
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800"
                        required>
                        @foreach ($mapels as $mapel)
                        <option value="{{ $mapel->id }}"
                            {{ in_array($mapel->id, old('mapel_ids', [])) ? 'selected' : '' }}>
                            {{ $mapel->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('mapel_ids')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full rounded-lg px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800"
                        required>
                    @error('password')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openCreate = false"
                        class="px-4 py-2 border rounded-full dark:border-gray-400">Batal</button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-full">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection