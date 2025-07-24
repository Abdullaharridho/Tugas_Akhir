@extends('sideadmin')

@if (session('successCount') !== null)
<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 10000)"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    x-transition>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 text-left max-w-md w-full">
        <h2 class="text-lg font-bold text-blue-600 dark:text-blue-400 mb-2">📥 Hasil Import</h2>
        <p class="text-gray-800 dark:text-gray-200 mb-2">
            <strong>{{ session('successCount') }}</strong> data berhasil diimport.
        </p>
        @if (session('failCount') > 0)
        <p class="text-red-600 dark:text-red-400 font-semibold mb-2">
            {{ session('failCount') }} data gagal diimport:
        </p>
        <ul class="list-disc pl-6 text-sm text-gray-700 dark:text-gray-300 max-h-40 overflow-y-auto">
            @foreach (session('failDetails') as $fail)
            <li>Baris {{ $fail['baris'] }} - NIK: {{ $fail['nik'] }} ({{ $fail['alasan'] }})</li>
            @endforeach
        </ul>
        @endif
        <div class="text-right mt-4">
            <button @click="show = false" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded">
                Tutup
            </button>
        </div>
    </div>
</div>
@endif

@section('konten')
<div class="container mx-auto p-6" x-data="santriData({{ json_encode($kelas) }}, {{ json_encode($kamar) }})">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">📋 Data Santri</h2>
        <a href="{{ route('datasantri.tambah') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-full shadow transition">
            ➕ Tambah Santri
        </a>
    </div>

    <!-- Form Import dan Export -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <div class="flex items-center gap-3 bg-white dark:bg-gray-800 px-4 py-3 rounded-xl shadow-md w-full md:w-auto">
            <form action="{{ route('datasantri.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
                @csrf
                <label for="importFile" class="flex items-center gap-2 cursor-pointer text-sm font-medium text-gray-700 dark:text-gray-200">
                    📁 Pilih File Excel
                    <input type="file" name="file" id="importFile" accept=".xls,.xlsx"
                        class="hidden" onchange="this.form.submit()">
                </label>
                <span class="text-xs text-gray-500 dark:text-gray-400">(xls / xlsx)</span>
            </form>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('datasantri.export') }}"
                class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-full shadow transition text-sm font-medium">
                📤 Export Excel
            </a>
            <a href="{{ asset('storage/Template.xlsx') }}"
                class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-full shadow transition text-sm font-medium"
                download>
                📄 Download Template
            </a>
        </div>
    </div>

    <!-- Pencarian -->
    <form method="GET" action="{{ route('datasantri.index') }}" class="mb-6 w-full sm:w-1/2" id="searchForm">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-300 text-lg">🔍</span>
            <div id="loadingSpinner" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
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
    <form method="GET" action="{{ route('datasantri.index') }}" class="mb-4">
        <div class="flex items-center gap-3">
            <label for="filterKelas" class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Kelas:</label>
            <select name="kelas" id="filterKelas"
                onchange="this.form.submit()"
                class="rounded-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelas as $item)
                <option value="{{ $item->id }}" {{ request('kelas') == $item->id ? 'selected' : '' }}>
                    {{ $item->nama }}
                </option>
                @endforeach
            </select>
        </div>
    </form>
    <!-- Tabel -->
    <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    @foreach(['Aksi', 'NIS', 'NIK', 'Nama', 'Tgl Lahir', 'Jenis Kelamin', 'Alamat', 'Ortu'] as $head)
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">{{ $head }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($santris as $index => $santri)
                <tr x-show="true" x-transition.opacity.delay.{{ $index * 100 }}ms class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('datasantri.detail', $santri->nis) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full transition">
                                🔍 Detail
                            </a>
                            <a href="{{ route('datasantri.edit', $santri->nis) }}"
                                class="bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded-full transition">
                                ✏️ Edit
                            </a>
                            <form action="{{ url('/data_santri/' . $santri->nis) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus santri ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 hover:bg-red-700 text-white py-1 px-4 rounded-full transition">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-xs text-gray-800 dark:text-gray-200">{{ $santri->nis }}</td>
                    <td class="px-4 py-2 text-xs text-gray-800 dark:text-gray-200">{{ $santri->nik }}</td>
                    <td class="px-4 py-2 text-xs text-gray-800 dark:text-gray-200">{{ $santri->nama }}</td>
                    <td class="px-4 py-2 text-xs text-gray-800 dark:text-gray-200">
                        {{ \Carbon\Carbon::parse($santri->tgllahir)->translatedFormat('d F Y') }}
                    </td>
                    <td class="px-4 py-2 text-xs text-gray-800 dark:text-gray-200">{{ $santri->jenis_kelamin }}</td>
                    <td class="px-4 py-2 text-xs text-gray-800 dark:text-gray-200">{{ $santri->alamat }}</td>
                    <td class="px-4 py-2 text-xs text-gray-800 dark:text-gray-200">{{ $santri->ortu }}</td>


                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function santriData(kelasList, kamarList) {
            return {
                showEdit: false,
                nis: '',
                nama: '',
                kelas: '',
                kamar: '',
                kelasList,
                kamarList,
                editSantri(santri) {
                    this.nis = santri.nis ?? '';
                    this.nama = santri.nama ?? '';
                    this.kelas = santri.kelas_id ?? '';
                    this.kamar = santri.kamar_id ?? '';
                    this.showEdit = true;
                }
            }
        }
    </script>
</div>
@endsection