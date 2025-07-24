<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" type="image/png" href="/storage/logo1.png">

    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        fadeInUp: "fadeInUp 0.5s ease-out both"
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': {
                                opacity: 0,
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: 1,
                                transform: 'translateY(0)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    @if (session('error'))
    <div class="bg-red-500 text-white text-sm p-3 rounded mb-4 shadow-md">
        {{ session('error') }}
    </div>
    @endif
</head>

<body class="bg-gray-100 min-h-screen p-6">
    @php
    $cards = [
    [
    'title' => 'Pelanggaran Ringan',
    'count' => $pelanggaran->ringan ?? 0,
    'color' => 'text-red-500',
    'daftar' => $keteranganRingan ?? collect(),
    ],
    [
    'title' => 'Pelanggaran Sedang',
    'count' => $pelanggaran->sedang ?? 0,
    'color' => 'text-yellow-500',
    'daftar' => $keteranganSedang ?? collect(),
    ],
    [
    'title' => 'Pelanggaran Berat',
    'count' => $pelanggaran->berat ?? 0,
    'color' => 'text-black',
    'daftar' => $keteranganBerat ?? collect(),
    ],
    [
    'title' => 'Total Tabungan',
    'count' => 'Rp ' . number_format($totalTabungan ?? 0),
    'color' => 'text-green-500',
    'daftar' => collect(), // kosongkan tabungan
    ],
    ];
    @endphp

    <div x-data="{ openModal: false, openModalTransaksi: false }" class="max-w-6xl mx-auto space-y-8">


        @if (session('success'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            x-init="setTimeout(() => show = false, 4000)" {{-- auto-close after 4s --}}>
            <div class="bg-white rounded-lg shadow-xl p-6 text-center max-w-sm w-full">
                <h2 class="text-lg font-bold text-green-600 mb-2">Berhasil</h2>
                <p class="text-gray-800">{{ session('success') }}</p>
                <button
                    @click="show = false"
                    class="mt-4 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded">
                    Oke
                </button>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">👋 Hai, {{ Auth::user()->name }}!</h1>
                <p class="text-sm text-gray-500">NIS: {{ Auth::user()->email ?? '-' }}</p>
            </div>
            <div class="text-gray-600">
                <svg class="h-10 w-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 15c2.389 0 4.63.588 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <!-- Kartu Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($cards as $card)
            <div
                x-data="{ open: false }"
                class="bg-white rounded-xl p-5 shadow-md hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02]">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-md font-medium text-gray-600 mb-1">{{ $card['title'] }}</h2>
                        <p class="text-3xl font-bold {{ $card['color'] }}">{{ $card['count'] }}</p>
                    </div>
                    @if($card['daftar']->isNotEmpty())
                    <button
                        @click="open = !open"
                        class="text-gray-400 hover:text-gray-700 transition"
                        :aria-expanded="open.toString()">
                        <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    @endif
                </div>

                @if ($card['daftar']->isNotEmpty())
                <div
                    x-show="open"
                    x-collapse
                    class="mt-3 text-sm text-gray-700 max-h-36 overflow-y-auto space-y-2">
                    <p class="font-semibold mb-1 text-gray-500">Detail Pelanggaran:</p>
                    <ul class="list-disc list-inside">
                        @foreach($card['daftar'] as $item)
                        <li>
                            <span class="font-semibold">Keterangan:</span> {{ $item->keterangan }}<br>
                            <span class="font-semibold">Tindakan:</span> {{ $item->tindakan }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endforeach
        </div>

@if($keteranganAbsensi->isNotEmpty())
    <div class="bg-white rounded-xl shadow p-6 my-4">
        <h2 class="text-lg font-semibold mb-3">📆 Keterangan Absensi</h2>
        <ul class="space-y-3 text-sm text-gray-700">
            @foreach($keteranganAbsensi as $item)
                <li class="border p-3 rounded-lg shadow-sm">
                    <p><span class="font-semibold">Tanggal:</span> {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}</p>
                    <p><span class="font-semibold">Keterangan:</span> {{ $item->keterangan }}</p>
                    <p><span class="font-semibold">Tindakan:</span> {{ $item->tindakan }}</p>
                </li>
            @endforeach
        </ul>
    </div>
@endif

        <div class="bg-white rounded-xl shadow p-6 my-4">
    <h2 class="text-lg font-semibold mb-3">📊 Grafik Tabungan Bulanan</h2>
    <canvas id="grafikTabungan" height="100"></canvas>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('grafikTabungan').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($grafikBulan->pluck('bulan')) !!},
            datasets: [
                {
                    label: 'Tabung',
                    data: {!! json_encode($grafikBulan->pluck('total_tabung')) !!},
                    backgroundColor: '#22c55e',
                    borderRadius: 5,
                    barPercentage: 0.4,
                },
                {
                    label: 'Ambil',
                    data: {!! json_encode($grafikBulan->pluck('total_ambil')) !!},
                    backgroundColor: '#ef4444',
                    borderRadius: 5,
                    barPercentage: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#374151', // text-gray-700
                        font: { size: 14 }
                    }
                },
            },
            scales: {
                x: {
                    stacked: false,
                    ticks: {
                        color: '#374151'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 5000,
                        color: '#374151'
                    }
                }
            }
        }
    });
</script>

        <!-- Tombol Aksi -->
        <div class="flex justify-center flex-wrap gap-4">
            <button @click="openModal = true"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition">
                🔒 Ubah Password
            </button>
            <button @click="openModalTransaksi = true"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition">
                💰 Riwayat Tabungan
            </button>
        </div>

        <!-- Modal Ubah Password -->
        <div x-show="openModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
            x-transition.opacity>
            <div class="bg-white w-full max-w-md rounded-xl p-6 shadow-lg relative" @click.away="openModal = false">
                <h2 class="text-xl font-bold text-gray-800 mb-4">🔒 Ganti Password</h2>
                <form method="POST" action="{{ route('user.update-password') }}">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <input type="password" name="current_password" placeholder="Password Lama"
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 @error('current_password') border-red-500 @enderror">
                        @error('current_password') <p class="text-sm text-red-500">{{ $message }}</p> @enderror

                        <input type="password" name="new_password" placeholder="Password Baru"
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 @error('new_password') border-red-500 @enderror">
                        @error('new_password') <p class="text-sm text-red-500">{{ $message }}</p> @enderror

                        <input type="password" name="new_password_confirmation" placeholder="Konfirmasi Password"
                            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 @error('new_password_confirmation') border-red-500 @enderror">
                        @error('new_password_confirmation') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end mt-6 space-x-2">
                        <button type="button" @click="openModal = false"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Riwayat Tabungan -->
        <div x-show="openModalTransaksi"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
            x-transition.opacity>
            <div class="bg-white w-full max-w-3xl rounded-xl p-6 shadow-lg relative" @click.away="openModalTransaksi = false">
                <h2 class="text-xl font-bold text-gray-800 mb-4">💳 Riwayat Tabungan</h2>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border text-sm text-center">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="border px-3 py-2">Tanggal</th>
                                <th class="border px-3 py-2">Jenis</th>
                                <th class="border px-3 py-2">Jumlah</th>
                                <th class="border px-3 py-2">Pengurus</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse($transaksi as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-3 py-2">{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                                <td class="border px-3 py-2 capitalize">{{ $item->jenis }}</td>
                                <td class="border px-3 py-2">Rp {{ number_format($item->jumlah) }}</td>
                                <td class="border px-3 py-2">{{ $item->pengurus->name ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-gray-500 py-4">Tidak ada transaksi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end mt-4">
                    <button @click="openModalTransaksi = false"
                        class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Tutup</button>
                </div>
            </div>
        </div>

        <!-- Tombol Logout -->
        <div class="text-center">
            <form method="POST" action="{{ route('logout') }}" @submit.prevent="if(confirm('Yakin ingin logout?')) $el.submit()">
                @csrf
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded-lg shadow">
                    🚪 Keluar
                </button>
            </form>
        </div>
    </div>
</body>

</html>