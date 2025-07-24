<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Santri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans">

<div class="max-w-xl mx-auto my-10 bg-white p-8 rounded-xl shadow-md" x-data="{ open: false }" x-init="setTimeout(() => open = true, 200)">

    {{-- Animasi dropdown tampil detail --}}
    <div x-show="open" x-transition.duration.500ms>
        {{-- Foto dan Nama --}}
        <div class="flex flex-col items-center mb-6">
            @php
                $foto = $santri->jenis_kelamin === 'Laki-laki' ? 'lakilaki.png' : 'perempuan.png';
            @endphp
            <img src="{{ asset('storage/' . $foto) }}" alt="Foto Santri"
                 class="w-36 h-36 rounded-full shadow-md object-cover">
            <h3 class="mt-4 text-xl font-semibold text-gray-800">{{ $santri->nama }}</h3>
            <p class="text-sm text-gray-600">NIS: {{ $santri->nis }}</p>
        </div>

        {{-- Informasi Detail --}}
        <div class="space-y-4 text-sm">
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <strong>NIK:</strong> <span class="ml-2">{{ $santri->nik }}</span>
            </div>
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <strong>Jenis Kelamin:</strong> <span class="ml-2">{{ $santri->jenis_kelamin }}</span>
            </div>
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <strong>Tanggal Lahir:</strong> <span class="ml-2">{{ \Carbon\Carbon::parse($santri->tgllahir)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <strong>Orang Tua:</strong> <span class="ml-2">{{ $santri->ortu }}</span>
            </div>
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <strong>Alamat:</strong> <span class="ml-2">{{ $santri->alamat }}</span>
            </div>
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <strong>No HP:</strong> <span class="ml-2">{{ $santri->kontak }}</span>
            </div>
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <strong>Kelas:</strong> <span class="ml-2">{{ $santri->kelasData->nama ?? '-' }}</span>
            </div>
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <strong>Kamar:</strong> <span class="ml-2">{{ $santri->kamarData->nama ?? '-' }}</span>
            </div>
        </div>

        {{-- Tombol Kembali --}}
        <div class="text-center mt-8">
            <a href="{{ route('datasantri.index') }}"
               class="inline-block bg-gray-700 hover:bg-gray-900 text-white px-6 py-2 rounded transition">
                🔙 Kembali
            </a>
        </div>
    </div>
</div>

</body>
</html>
