@extends('sideguru')

@section('konten')

<div x-data x-init="$el.classList.add('opacity-0'); setTimeout(() => $el.classList.remove('opacity-0'), 100)"
    class="max-w-6xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-2xl transition-opacity duration-700 ease-out opacity-100">

    <h2 class="text-2xl font-bold text-center mb-6 text-gray-800 dark:text-white tracking-wide">📅 Rekap Absensi</h2>

    <div class="flex justify-end mb-4">
        <a href="{{ route('absensi.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 dark:bg-blue-400 dark:hover:bg-blue-500 transition-all transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" />
            </svg>
            Tambah Absensi
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border border-gray-300 dark:border-gray-700 shadow-sm rounded-lg overflow-hidden transition-all">
            <thead class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Tanggal</th>
                    <th class="px-4 py-2 border">Mata Pelajaran</th>
                    <th class="px-4 py-2 border">Kelas</th>
                    <th class="px-4 py-2 border">Guru</th>
                    <th class="px-4 py-2 border">Hadir</th>
                    <th class="px-4 py-2 border">Total Siswa</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapAbsensi as $index => $rekap)
                <tr class="border border-gray-300 dark:border-gray-600 hover:bg-blue-50 dark:hover:bg-gray-700 transition-all duration-300">
                    <td class="px-4 py-2 text-gray-800 dark:text-white text-center">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 text-gray-800 dark:text-white">{{ $rekap->tanggal }}</td>
                    <td class="px-4 py-2 text-gray-800 dark:text-white">{{ $rekap->mapel->nama ?? '-' }}</td>
                    <td class="px-4 py-2 text-gray-800 dark:text-white">{{ $rekap->kelas->nama ?? '-' }}</td>
                    <td class="px-4 py-2 text-gray-800 dark:text-white">{{ $rekap->guru->name ?? '-' }}</td>
                    <td class="px-4 py-2 text-green-600 dark:text-green-400 font-semibold">{{ $rekap->hadir }}</td>
                    <td class="px-4 py-2 text-gray-700 dark:text-gray-200">{{ $rekap->total_siswa }}</td>
                    <td class="px-4 py-2 text-center">
                        <a href="{{ route('absensi.edit', [
                            'tanggal' => $rekap->tanggal,
                            'kelas' => $rekap->id_kelas,
                            'mapel' => $rekap->id_mapel
                        ]) }}"
                            class="inline-flex items-center gap-2 px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 dark:bg-yellow-400 dark:hover:bg-yellow-500 transition-all transform hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12H9m12 0A9 9 0 116 3.75" />
                            </svg>
                            Lihat/Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-gray-500 dark:text-gray-300">Belum ada data absensi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
