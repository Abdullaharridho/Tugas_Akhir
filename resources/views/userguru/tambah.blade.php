@extends('layout')

@section('content')
<h2 class="text-2xl font-bold text-center mb-6 text-gray-800 dark:text-white tracking-wide">📝 Form Absensi</h2>

<div x-data x-init="$el.classList.add('opacity-0'); setTimeout(() => $el.classList.remove('opacity-0'), 100)"
    class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-2xl transition-opacity duration-700 opacity-100">

    <form action="{{ route('absensi.store') }}" method="POST" x-data="absensiForm">
        @csrf

        <!-- Tanggal -->
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">
                <i class="fa-solid fa-calendar-days mr-1"></i> Tanggal
            </label>
            <input type="date" name="tanggal" required
                class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
        </div>

        <!-- Kelas -->
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">
                <i class="fa-solid fa-school mr-1"></i> Kelas
            </label>
            <select name="kelas_id" required x-on:change="fetchSantri($event.target.value)"
                class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $k)
                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Mata Pelajaran -->
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">
                <i class="fa-solid fa-book-open mr-1"></i> Mata Pelajaran
            </label>
            <select name="mapel_id" required
                class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                @foreach($mapel as $m)
                <option value="{{ $m->id }}">{{ $m->nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tabel Absensi -->
        <div class="overflow-x-auto mt-6">
            <table class="w-full text-sm border border-gray-300 dark:border-gray-600 rounded-md overflow-hidden shadow-md">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white text-left">
                    <tr>
                        <th class="px-4 py-2 border">NIS</th>
                        <th class="px-4 py-2 border">Nama Santri</th>
                        <th class="px-4 py-2 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="santri in santriList" :key="santri.nis">
                        <tr class="border hover:bg-blue-50 dark:hover:bg-gray-700 transition-all duration-200">
                            <td class="px-4 py-2 border text-gray-800 dark:text-white">
                                <span x-text="santri.nis"></span>
                                <input type="hidden" :name="'nis[]'" x-bind:value="santri.nis">
                            </td>
                            <td class="px-4 py-2 border text-gray-800 dark:text-white">
                                <span x-text="santri.nama"></span>
                                <input type="hidden" :name="'nama[]'" x-bind:value="santri.nama">
                            </td>
                            <td class="px-4 py-2 border">
                                <div class="flex flex-wrap gap-2">
                                    <label class="text-green-600 dark:text-green-400"><input type="radio" :name="'status[' + santri.nis + ']'" value="Hadir" required> Hadir</label>
                                    <label class="text-red-500 dark:text-red-400"><input type="radio" :name="'status[' + santri.nis + ']'" value="Alpha" required> Alpha</label>
                                    <label class="text-yellow-600 dark:text-yellow-400"><input type="radio" :name="'status[' + santri.nis + ']'" value="Izin" required> Izin</label>
                                    <label class="text-blue-600 dark:text-blue-400"><input type="radio" :name="'status[' + santri.nis + ']'" value="Sakit" required> Sakit</label>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Submit -->
        <button type="submit"
            class="mt-6 w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i> Simpan Absensi
        </button>
    </form>
</div>

<!-- Alpine.js logic -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('absensiForm', () => ({
            santriList: [],

            fetchSantri(kelas) {
                if (!kelas) {
                    this.santriList = [];
                    return;
                }

                fetch(`/get-santri/${kelas}`)
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        this.santriList = Array.isArray(data) ? data : [];
                    })
                    .catch(error => {
                        console.error('❌ Error fetching santri:', error);
                    });
            }
        }));
    });
</script>
@endsection
