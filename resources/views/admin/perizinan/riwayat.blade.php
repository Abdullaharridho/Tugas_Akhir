<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/storage/logo1.png">
    <title>Riwayat Perizinan</title>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen text-gray-900">

    <div class="max-w-5xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-lg border border-gray-200"
        x-data="{ openModal: false, selectedIzin: {} }">
        <h2 class="text-3xl font-bold text-gray-800 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-blue-600"></i> Riwayat Perizinan
        </h2>
        <p class="mb-6 text-gray-600">
            <span class="font-medium">Santri:</span> <strong>{{ $santri->nama }}</strong>
            <span class="ml-4 font-medium">NIS:</span> <strong>{{ $santri->nis }}</strong>
        </p>
        <a href="{{ route('perizinan.tampil') }}" class="text-yellow-600 hover:underline hover:text-yellow-800 transition">
                                <i class="fa-solid fa-backward"></i> Kembali Ke Perizinan
                            </a>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-gray-300 divide-y divide-gray-200 bg-white shadow-sm rounded">
                <thead class="bg-blue-50 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Jenis</th>
                        <th class="px-4 py-2 text-left">Keterangan</th>
                        <th class="px-4 py-2 text-left">Tanggal Kembali</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($perizinan as $izin)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-4 py-2">{{ $izin->tanggal }}</td>
                        <td class="px-4 py-2 capitalize">{{ $izin->statuspesan }}</td>
                        <td class="px-4 py-2">{{ $izin->keterangan }}</td>
                        <td class="px-4 py-2">{{ $izin->tanggal_kembali ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full
                            {{ $izin->statuspesan === 'Kembali' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                <i class="fa-solid {{ $izin->statuspesan === 'Kembali' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                                {{
    $izin->statuspesan === 'kembali' ? 'Sudah Kembali' : 
    ($izin->statuspesan === 'terlambat' ? 'Terlambat' : 'Masih Izin') 
}}
                            </span>
                        </td>
                        <td class="px-4 py-2 space-x-2 text-sm whitespace-nowrap">
                            <button class="text-blue-600 hover:underline hover:text-blue-800 transition"
                                @click="selectedIzin = {
                                nis: '{{ $izin->nis }}',
                                nama: '{{ $izin->santri->nama ?? '-' }}',
                                tanggal: '{{ $izin->tanggal }}',
                                statuspesan: '{{ $izin->statuspesan }}',
                                keterangan: '{{ $izin->keterangan }}',
                                tanggal_kembali: '{{ $izin->tanggal_kembali ?? '-' }}'
                            }; openModal = true">
                                <i class="fa-solid fa-eye"></i> Detail
                            </button>
                            
                            <a href="{{ route('perizinan.edit', $izin->id) }}" class="text-yellow-600 hover:underline hover:text-yellow-800 transition">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>

                            <form action="{{ route('perizinan.hapus', $izin->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline hover:text-red-800 transition">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                            @if ($izin->statuspesan === 'terlambat')
                            <a href="{{ route('perizinan.surat_terlambat', $izin->id) }}"
                                class="text-red-600 hover:underline hover:text-red-800 transition inline-block mt-1">
                                <i class="fa-solid fa-file-pdf"></i> Surat Keterlambatan
                            </a>
                            @endif
                             <a href="{{ route('perizinan.getSurat', $izin->id) }}" class="text-green-600 hover:text-green-800 hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-file-lines"></i> Surat
                        </a>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500 italic">Belum ada riwayat perizinan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modal Detail -->
        <div x-show="openModal" x-transition.opacity.duration.300ms
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto shadow-lg relative transform transition-all scale-95"
                @click.away="openModal = false">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-blue-500"></i> Detail Perizinan
                </h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li><strong>NIS:</strong> <span x-text="selectedIzin.nis"></span></li>
                    <li><strong>Nama:</strong> <span x-text="selectedIzin.nama"></span></li>
                    <li><strong>Tanggal:</strong> <span x-text="selectedIzin.tanggal"></span></li>
                    <li><strong>Jenis:</strong> <span x-text="selectedIzin.statuspesan"></span></li>
                    <li><strong>Keterangan:</strong> <span x-text="selectedIzin.keterangan"></span></li>
                    <li><strong>Tanggal Kembali:</strong> <span x-text="selectedIzin.tanggal_kembali"></span></li>
                </ul>
                <button @click="openModal = false"
                    class="mt-6 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                    <i class="fa-solid fa-xmark mr-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>

</body>

</html>