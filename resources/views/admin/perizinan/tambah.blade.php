<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Perizinan Santri</title>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-blue-50 to-white min-h-screen flex items-center justify-center text-gray-900">

    @php
        $santri = $santri ?? null;
        $today = now()->toDateString(); 
        $maxReturn = now()->addDays(7)->toDateString(); 
    @endphp

    <div 
        x-data="{
            show: false,
            nis: '{{ old('nis', $santri->nis ?? '') }}',
            nama: '{{ old('nis') ? \App\Models\Datasantri::where('nis', old('nis'))->value('nama') : ($santri->nama ?? '') }}',
            jenis: '{{ old('jenis_keterangan', 'izin') }}'
        }" 
        x-init="setTimeout(() => show = true, 100)" 
        x-show="show" 
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 scale-95 translate-y-5"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        class="w-full max-w-lg p-8 bg-white shadow-xl rounded-2xl"
    >
        <h2 class="text-2xl font-bold mb-6 text-blue-700">Tambah Perizinan Santri</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('perizinan.simpan') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                <input type="text" name="nis" x-model="nis" readonly class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Santri</label>
                <input type="text" x-model="nama" readonly class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Keterangan</label>
                <select name="jenis_keterangan" x-model="jenis" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                </select>
            </div>

            <div x-show="jenis === 'izin'" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="origin-top"
            >
                <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Izin</label>
                <input type="text" name="alasan_izin" value="{{ old('alasan_izin') }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Contoh: Pulang ke rumah">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal"
                    value="{{ old('tanggal', $today) }}"
                    min="{{ $today }}"
                    class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none" readonly>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali"
                    value="{{ old('tanggal_kembali') }}"
                    min="{{ $today }}"
                    max="{{ $maxReturn }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                <p class="text-xs text-gray-500 mt-1">* Wajib diisi, maksimal 7 hari dari hari ini</p>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200">
                    Simpan
                </button>
            </div>
        </form>
    </div>

</body>

</html>
