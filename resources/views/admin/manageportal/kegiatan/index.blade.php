@extends('sideadmin')

@section('konten')
    <div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
         x-init="$watch('darkMode', value => { 
             localStorage.setItem('darkMode', value); 
             document.documentElement.classList.toggle('dark', value); 
         })"
         class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-white transition-all duration-300 ease-in-out">

        <!-- Tombol Toggle Dark Mode -->
        <button @click="darkMode = !darkMode"
            class="fixed top-4 right-4 bg-blue-500 dark:bg-yellow-400 text-white dark:text-black p-2 rounded-full shadow-lg transition-transform duration-300 hover:scale-110 z-50">
            <span x-show="!darkMode">🌙</span>
            <span x-show="darkMode">☀️</span>
        </button>

        <!-- Konten -->
        <div class="max-w-6xl mx-auto p-6">
            <h1 class="text-3xl font-bold text-center mb-8">
                📅 Manajemen Kegiatan
            </h1>

            <!-- Tombol Tambah -->
            <div class="flex justify-end mb-6">
                <a href="{{ route('admin.manageportal.kegiatan.tambah') }}"
                   class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow transition duration-300">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Kegiatan
                </a>
            </div>

            <!-- Tabel -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl rounded-xl overflow-hidden">
                <table class="w-full text-sm text-left text-gray-800 dark:text-gray-200">
                    <thead class="text-xs uppercase bg-gray-500 dark:bg-gray-700 text-white">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Deskripsi</th>
                            <th class="px-6 py-3">Gambar</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatan as $index => $item)
                            <tr class="bg-white dark:bg-gray-900 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $item->judul }}</td>
                                <td class="px-6 py-4">{{ Str::limit($item->deskripsi, 50) }}</td>
                                <td class="px-6 py-4">
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar"
                                         class="w-24 h-14 object-cover rounded shadow mx-auto">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.manageportal.kegiatan.edit', $item->id) }}"
                                       class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md shadow mr-2 transition">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.manageportal.kegiatan.hapus', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md shadow transition"
                                            onclick="return confirm('Yakin ingin menghapus kegiatan ini?');">
                                            <i class="fa-solid fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 dark:text-gray-400 py-8">
                                    Tidak ada data kegiatan yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Include Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
