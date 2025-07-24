<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Perizinan</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md" 
         x-data="{
            jenis: '{{ old('jenis_keterangan', Str::contains($perizinan->keterangan, 'sakit') ? 'sakit' : 'izin') }}',
            tanggal: '{{ old('tanggal', $perizinan->tanggal) }}',
            get maxTanggalKembali() {
                const t = new Date(this.tanggal);
                t.setDate(t.getDate() + 7);
                return t.toISOString().split('T')[0];
            }
        }">

        <h2 class="text-2xl font-bold mb-6">Edit Perizinan</h2>

        <form action="{{ route('perizinan.update', $perizinan->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-semibold">NIS</label>
                <input type="text" name="nis" value="{{ old('nis', $perizinan->nis) }}"
                    class="w-full p-3 border border-gray-300 rounded-lg" readonly>
            </div>

            <div>
                <label class="block font-semibold">Tanggal Izin</label>
                <input type="date" name="tanggal" x-model="tanggal"
                    value="{{ old('tanggal', $perizinan->tanggal) }}"
                    class="w-full p-3 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block font-semibold">Jenis Keterangan</label>
                <select name="jenis_keterangan" x-model="jenis"
                    class="w-full p-3 border border-gray-300 rounded-lg">
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                </select>
            </div>

            <!-- Alasan Izin -->
            <div x-show="jenis === 'izin'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="origin-top">
                <label class="block font-semibold">Alasan Izin</label>
                <input type="text" name="alasan_izin"
                    value="{{ old('alasan_izin', Str::after($perizinan->keterangan, 'izin, ')) }}"
                    class="w-full p-3 border border-gray-300 rounded-lg"
                    placeholder="Misal: Pulang keluarga">
            </div>

           <div>
                <label class="block font-semibold">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali"
                    value="{{ old('tanggal_kembali', $perizinan->tanggal_kembali) }}"
                    :min="tanggal"
                    :max="maxTanggalKembali"
                    class="w-full p-3 border border-gray-300 rounded-lg">
                <p class="text-xs text-gray-500 mt-1">* Maksimal 7 hari dari tanggal izin</p>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        @if ($errors->any())
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 p-4 rounded">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

</body>
</html>
