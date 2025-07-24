@extends('sideadmin')

@section('konten')
   

    <div class="max-w-6xl mx-auto p-6 animate-fadeInUp">
        <h1 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-8">
            📽️ Manajemen Slide
        </h1>

        <!-- Tombol Tambah -->
        <div class="flex justify-end mb-6">
            <a href="{{ route('admin.manageportal.tambah') }}"
                class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-md transition duration-300 transform hover:scale-105">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Slide
            </a>
        </div>

        <!-- Tabel Data Slide -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden animate-fadeInUp">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="text-xs uppercase bg-gray-500 dark:bg-gray-700 text-gray-800 dark:text-white">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Gambar</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($slides as $index => $slide)
                        <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-300">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium">{{ $slide->nama }}</td>
                            <td class="px-6 py-4">
                                <img src="{{ asset('storage/' . $slide->gambar) }}" alt="Slide Image"
                                    class="w-24 h-14 object-cover rounded shadow-md mx-auto">
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.manageportal.edit', $slide->id) }}"
                                    class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md shadow transition duration-300 transform hover:scale-105 mr-2">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.manageportal.hapus', $slide->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md shadow transition duration-300 transform hover:scale-105"
                                        onclick="return confirm('Yakin ingin menghapus slide ini?');">
                                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 dark:text-gray-400 py-8">
                                Belum ada data slide yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
