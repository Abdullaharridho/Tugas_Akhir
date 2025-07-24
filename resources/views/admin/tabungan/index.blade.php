@extends('sideadmin')

@section('konten')
<div class="container mx-auto p-6"
    x-data="{
        openTabung: false,
        openAmbil: false,
        nis: '',
        nama: '',
        setModal(nisSantri, namaSantri, jenis) {
            this.nis = nisSantri;
            this.nama = namaSantri;
            this.openTabung = (jenis === 'tabung');
            this.openAmbil = (jenis === 'ambil');
        }
    }">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
        <h2 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white text-center">📥 Daftar Tabungan Santri</h2>
          <form method="GET" action="{{ route('tabungan.index') }}" class="mb-6 w-full sm:w-1/2" id="searchForm">
    <div class="relative">
        <!-- Search icon -->
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-300 text-lg">🔍</span>

        <!-- Loading spinner -->
        <div id="loadingSpinner" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
            <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                </path>
            </svg>
        </div>

        <!-- Input -->
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
    const delay = 1000; // 3 detik

    searchInput.addEventListener('input', function () {
        clearTimeout(typingTimer);
        spinner.classList.remove('hidden'); // Tampilkan spinner

        typingTimer = setTimeout(() => {
            searchForm.submit();
        }, delay);
    });

    // Sembunyikan spinner saat form selesai dimuat ulang
    window.addEventListener('pageshow', () => {
        spinner.classList.add('hidden');
    });
</script>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg shadow-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">NIS</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Nama</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Tanggal</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Total</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Tabung</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Ambil</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Pengurus</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                    @foreach($santri as $s)
                    @php
                    $transaksiTerbaru = $s->latestTransaksi;
                    $totalTabung = $s->tabung;
                    $totalAmbil = $s->ambil;
                    $saldo = $s->saldo;
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-3">{{ $s->nis }}</td>
                        <td class="px-6 py-3">{{ $s->nama }}</td>
                        <td class="px-6 py-3">{{ $transaksiTerbaru->tanggal ?? '-' }}</td>
                        <td class="px-6 py-3">Rp. {{ number_format($saldo, 0, ',', '.') }}</td>
                        <td class="px-6 py-3">Rp. {{ number_format($totalTabung, 0, ',', '.') }}</td>
                        <td class="px-6 py-3">Rp. {{ number_format($totalAmbil, 0, ',', '.') }}</td>
                       <td class="px-6 py-3">{{ $transaksiTerbaru->pengurus->name ?? '-' }}</td>
                        <td class="px-6 py-3 flex flex-wrap gap-2">
                            <button @click="setModal('{{ $s->nis }}', '{{ $s->nama }}', 'tabung')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-full transition">
                                ➕ Tabung
                            </button>
                            <button @click="setModal('{{ $s->nis }}', '{{ $s->nama }}', 'ambil')"
                                class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-full transition">
                                🔻 Ambil
                            </button>
                            <a href="{{ route('tabungan.riwayat', ['nis' => $s->nis]) }}"
                                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-full transition">
                                📜 Riwayat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    {{-- Modal Tambah Tabungan --}}
    <div x-show="openTabung" x-cloak class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md w-full max-w-md mx-4"
            @click.away="openTabung = false" x-transition>
            <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">➕ Tambah Tabungan</h3>
            <form action="{{ route('tabungan.simpan') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="nis" x-model="nis">

                <div>
                    <label class="block text-sm font-medium mb-1">Jumlah Tabungan</label>
                    <input type="number" name="tabung" class="w-full bg-gray-100 dark:bg-gray-700 p-2 rounded-lg" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openTabung = false" class="px-4 py-2 border rounded-full dark:border-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-full">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Ambil Tabungan --}}
    <div x-show="openAmbil" x-cloak class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md w-full max-w-md mx-4"
            @click.away="openAmbil = false" x-transition>
            <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">🔻 Ambil Tabungan</h3>
            <form action="{{ route('tabungan.simpan') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="nis" x-model="nis">

                <div>
                    <label class="block text-sm font-medium mb-1">Jumlah Pengambilan</label>
                    <input type="number" name="ambil" class="w-full bg-gray-100 dark:bg-gray-700 p-2 rounded-lg" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openAmbil = false" class="px-4 py-2 border rounded-full dark:border-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-full">
                        Ambil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection