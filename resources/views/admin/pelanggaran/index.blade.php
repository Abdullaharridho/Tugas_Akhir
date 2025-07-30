@extends('sideadmin')

@section('konten')
<div x-data="{ openModal: false, selectedPelanggaran: {} }">

    @php
    $notifList = collect();
    foreach ($santri as $s) {
        $pelanggarans = $pelanggaranGrouped[$s->nis] ?? collect();
        $notifList = $notifList->merge(
            $pelanggarans->filter(fn($p) => str_contains($p->tindakan, '-'))
        );
    }
    @endphp

    @if ($notifList->isNotEmpty())
    <div class="mb-4 px-4 sm:px-6">
        <div class="bg-red-100 dark:bg-red-700 text-red-800 dark:text-red-100 border border-red-300 dark:border-red-600 rounded-lg p-4">
            <h3 class="font-semibold text-sm mb-2 flex items-center gap-2">
                <i class="fas fa-bell text-red-500"></i>
                Notifikasi Tindakan Khusus ({{ $notifList->count() }})
            </h3>
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach ($notifList as $item)
                <li>
                    <button
                        @click="selectedPelanggaran = {{ json_encode([
                            'id' => $item->id,
                            'tindakan' => $item->tindakan,
                            'keterangan' => $item->keterangan,
                        ]) }}; openModal = true"
                        class="text-blue-600 hover:underline">
                        {{ $item->santri->nama ?? 'Nama tidak ditemukan' }} - {{ $item->tindakan }} - {{ $item->keterangan }}
                    </button>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- TABEL UTAMA --}}
    <div class="container mx-auto px-4 sm:px-6 py-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center">📑 Data Pelanggaran</h2>

            {{-- Form Pencarian --}}
            <form method="GET" action="{{ route('pelanggaran.index') }}" class="mb-6 w-full sm:w-2/3 md:w-1/2" id="searchForm">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-300 text-lg">🔍</span>
                    <div id="loadingSpinner" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                        <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                            </path>
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        placeholder="Cari NIS atau Nama..."
                        value="{{ request('search') }}"
                        class="w-full pl-10 pr-10 py-2 rounded-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </form>

            <script>
                const searchInput = document.getElementById('searchInput');
                const searchForm = document.getElementById('searchForm');
                const spinner = document.getElementById('loadingSpinner');

                let typingTimer;
                const delay = 1000;

                searchInput.addEventListener('input', function () {
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

            {{-- Tabel Data --}}
            <div class="overflow-x-auto">
                <table class="min-w-[900px] divide-y divide-gray-200 dark:divide-gray-700 rounded-lg shadow-md text-xs">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">NIS</th>
                            <th class="px-4 py-2 text-left font-semibold">Nama</th>
                            <th class="px-4 py-2 text-left font-semibold">Ringan</th>
                            <th class="px-4 py-2 text-left font-semibold">Sedang</th>
                            <th class="px-4 py-2 text-left font-semibold">Berat</th>
                            <th class="px-4 py-2 text-left font-semibold">Keterangan</th>
                            <th class="px-4 py-2 text-left font-semibold">Tindakan</th>
                            <th class="px-4 py-2 text-left font-semibold">Pengurus</th>
                            <th class="px-4 py-2 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                        @foreach ($santri as $s)
                        @php
                            $pelanggaran = $latestPelanggaran->has($s->nis) ? $latestPelanggaran[$s->nis]->first() : null;
                            $allPelanggaran = $pelanggaranGrouped[$s->nis] ?? collect();
                            $allKeterangan = $allPelanggaran->pluck('keterangan')->filter()->unique()->implode(', ');
                            $allTindakan = $allPelanggaran->pluck('tindakan')->filter()->unique()->implode(', ');
                            $pengurusNama = $pelanggaran->pengurus->name ?? '-';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-2">{{ $s->nis }}</td>
                            <td class="px-4 py-2">{{ $s->nama }}</td>
                            <td class="px-4 py-2">{{ $allPelanggaran->where('kategori', 'ringan')->count() }}</td>
                            <td class="px-4 py-2">{{ $allPelanggaran->where('kategori', 'sedang')->count() }}</td>
                            <td class="px-4 py-2">{{ $allPelanggaran->where('kategori', 'berat')->count() }}</td>
                            <td class="px-4 py-2 text-[11px] leading-snug max-w-xs break-words whitespace-normal">{{ $allKeterangan ?: '-' }}</td>
                            <td class="px-4 py-2 text-[11px] leading-snug max-w-xs break-words whitespace-normal">{{ $allTindakan ?: '-' }}</td>
                            <td class="px-4 py-2 text-xs">{{ $pengurusNama }}</td>
                            <td class="px-4 py-2">
                                <div class="flex flex-wrap gap-2 text-xs max-w-[200px] sm:max-w-full">
                                    <a href="{{ route('pelanggaran.tambah', ['nis' => $s->nis, 'nama' => $s->nama]) }}"
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-full transition">
                                        ➕ Tambah
                                    </a>
                                    <a href="{{ route('pelanggaran.riwayat', $s->nis) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full transition">
                                        📜 Riwayat
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div x-show="openModal" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        @click.away="openModal = false">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-md mx-2">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">🛠️ Edit Tindakan Khusus</h2>
            <form :action="'/pelanggaran/update-tindakan/' + selectedPelanggaran.id" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tindakan</label>
                    <input type="text" name="tindakan" x-model="selectedPelanggaran.tindakan"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan</label>
                    <textarea name="keterangan" x-model="selectedPelanggaran.keterangan" readonly
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm"
                        rows="3"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openModal = false"
                        class="px-4 py-2 border rounded dark:border-gray-400">Batal</button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
