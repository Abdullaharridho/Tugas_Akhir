@extends('sideadmin')

@section('konten')

<div class="p-6  max-w-full overflow-x-auto bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 rounded-lg shadow-lg transition-colors duration-300">

    <h2 class="text-3xl font-bold mb-6">📋 Daftar Perizinan</h2>

    <form method="GET" action="{{ route('perizinan.tampil') }}" class="mb-6 w-full sm:w-1/2" id="searchForm">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-300 text-lg">🔍</span>
            <div id="loadingSpinner" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
            </div>
            <input
                type="text"
                name="search"
                id="searchInput"
                placeholder="Cari NIS atau Nama..."
                value="{{ request('search') }}"
                class="w-full pl-10 pr-10 py-2 rounded-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
        </div>
        <script>
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');
            const spinner = document.getElementById('loadingSpinner');

            let typingTimer;
            const delay = 1000;

            searchInput.addEventListener('input', function() {
                clearTimeout(typingTimer);
                spinner.classList.remove('hidden');
                typingTimer = setTimeout(() => {
                    searchForm.submit();
                }, delay);
            });

            window.addEventListener('pageshow', () => {
                spinner.classList.add('hidden');
            });
        </script>
    </form>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <table class="min-w-full border border-gray-300 dark:border-gray-700 text-sm">
            <thead>
                <tr class="bg-blue-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                    <th class="border px-4 py-3">No</th>
                    <th class="border px-4 py-3">NIS</th>
                    <th class="border px-4 py-3">Nama</th>
                    <th class="border px-4 py-3">Tanggal</th>
                    <th class="border px-4 py-3">Status</th>
                    <th class="border px-4 py-3">Keterangan</th>
                    <th class="border px-4 py-3">Tgl Kembali</th>
                    <th class="border px-4 py-3">Pengurus</th>
                    <th class="border px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="animate-fade-in">
                @foreach ($santri as $index => $s)
                @php
                $izin = $perizinan[$s->nis] ?? null;
                @endphp

                <tr class="transition-all duration-300 ease-in-out hover:scale-[1.01] hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                    <td class="border px-4 py-2">{{ $s->nis }}</td>
                    <td class="border px-4 py-2">{{ $s->nama }}</td>

                    @if (!$izin)
                    <td colspan="4" class="border px-4 py-2 text-center italic text-gray-400 dark:text-gray-500">Belum ada data perizinan</td>
                    <td class="border px-4 py-2">-</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('perizinan.tambah', ['nis' => $s->nis]) }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1 transition duration-300">
                            <i class="fa-solid fa-plus-circle"></i> Tambah
                        </a>
                    </td>
                    @else
                    <td class="border px-4 py-2">{{ $izin->tanggal }}</td>
                    <td class="border px-4 py-2">
                        @if($izin->statuspesan === 'kembali')
                        <span class="text-green-600 font-semibold">
                            <i class="fa-solid fa-check-circle"></i> Kembali
                        </span>
                        @elseif($izin->statuspesan === 'terlambat')
                        <span class="text-red-600 font-semibold">
                            <i class="fa-solid fa-clock"></i> Terlambat
                        </span>
                        @else
                        <span class="text-yellow-600 font-semibold">
                            <i class="fa-solid fa-hourglass-half"></i> Masih Izin
                        </span>
                        @endif
                    </td>
                    <td class="border px-4 py-2">{{ $izin->keterangan }}</td>
                    <td class="border px-4 py-2">{{ $izin->tanggal_kembali ?? '-' }}</td>
                    <td class="border px-4 py-2 whitespace-nowrap">{{ $izin->pengurus->name ?? '-' }}</td>
                    <td class="border px-4 py-2 space-y-1">
                        <a href="{{ route('perizinan.tambah', ['nis' => $s->nis]) }}" class="text-blue-600 hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-plus-circle"></i> Tambah
                        </a>
                        <a href="{{ route('perizinan.riwayat', ['nis' => $s->nis]) }}" class="text-indigo-600 hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
                        </a>

                        @if ($izin->statuspesan !== 'kembali' && $izin->statuspesan !== 'terlambat')

                        <form action="{{ route('perizinan.kembali', $izin->id) }}" method="POST" onsubmit="return confirm('Apakah santri sudah kembali?')">
                            @csrf
                            <button type="submit" class="text-green-600 hover:underline flex items-center gap-1">
                                <i class="fa-solid fa-arrow-left"></i> Sudah Kembali
                            </button>
                        </form>
                        <a href="{{ route('perizinan.getSurat', $izin->id) }}" class="text-green-600 hover:text-green-800 hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-file-lines"></i> Surat
                        </a>
                        @endif
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection