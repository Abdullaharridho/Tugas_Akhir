@extends('sideguru')

@section('konten')
@if (session('error'))
<div class="bg-red-500 text-white text-sm p-3 rounded mb-4 shadow-md">
    {{ session('error') }}
</div>
@endif
<div class="container mx-auto p-6 space-y-6">
    {{-- Ucapan Selamat Datang --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            👋 Selamat Datang, {{ Auth::user()->name }}
            <h2 class="text-1xl font-sans text-gray-600 dark:text-white">
                Email, {{ Auth::user()->email }}
            </h2>
        </h2>
        <p class="text-gray-600 dark:text-gray-300 mt-2">Semoga harimu menyenangkan dan penuh semangat mengajar!</p>
    </div>

    {{-- Card Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Card 1: Absensi Hari Ini --}}
        <div class="bg-blue-100 dark:bg-blue-900 text-blue-900 dark:text-blue-100 rounded-xl p-5 shadow">
            <h3 class="text-lg font-semibold mb-2">📅 Absensi Hari Ini</h3>
            <p class="text-3xl font-bold">{{ $jumlahAbsensiHariIni }}</p>
        </div>

        {{-- Card 2: Mapel yang Diajarkan --}}
        <div class="bg-green-100 dark:bg-green-900 text-green-900 dark:text-green-100 rounded-xl p-5 shadow">
            <h3 class="text-lg font-semibold mb-2">📘 Mapel yang Diajarkan</h3>
            <p class="text-xl font-bold">
                @if (!empty($mapel))
                {{ implode(', ', $mapel) }}
                @else
                Belum ada mata pelajaran
                @endif
            </p>
        </div>

        {{-- Card 3: Total Kelas --}}
        <div class="bg-yellow-100 dark:bg-yellow-900 text-yellow-900 dark:text-yellow-100 rounded-xl p-5 shadow">
            <h3 class="text-lg font-semibold mb-2">🏫 Total Kelas</h3>
            <p class="text-3xl font-bold">{{ $totalKelas }}</p>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Grafik Absensi --}}
    <div class="col-span-2 bg-white dark:bg-gray-800 shadow-md rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">📊 Grafik Absensi Bulanan</h3>
        <canvas id="absensiChart" class="w-full h-64"></canvas>
    </div>

    {{-- Sidebar Daftar Guru --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 max-h-96 overflow-y-auto">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">👩‍🏫 Daftar Guru & Mapel</h3>
        <ul class="space-y-2">
            @foreach ($daftarGuru as $guru)
                <li>
                    <span class="font-medium text-gray-800 dark:text-white">{{ $guru->name }}</span>
                    <br>
                    <span class="text-sm text-gray-600 dark:text-gray-300 italic">
                        @if ($guru->mapel->count())
                            {{ $guru->mapel->pluck('nama')->join(', ') }}
                        @else
                            <span class="text-red-500">Belum ada mapel</span>
                        @endif
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
{{-- Canvas grafik --}}
<canvas id="absensiChart" class="w-full h-64"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Plugin panah custom
    const arrowPlugin = {
        id: 'arrowPlugin',
        afterDatasetsDraw(chart, args, options) {
            const {ctx, data, chartArea: {top, bottom, left, right}} = chart;
            const meta = chart.getDatasetMeta(0);
            const lastPoint = meta.data[meta.data.length - 1];

            if (!lastPoint) return;

            const x = lastPoint.x;
            const y = lastPoint.y;

            const size = 10;
            ctx.save();
            ctx.beginPath();
            ctx.moveTo(x, y);
            ctx.lineTo(x + size, y - size / 2);
            ctx.lineTo(x + size, y + size / 2);
            ctx.closePath();
            ctx.fillStyle = meta.dataset.borderColor;
            ctx.fill();
            ctx.restore();
        }
    };

    const ctx = document.getElementById('absensiChart').getContext('2d');

    const absensiChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Jumlah Absensi',
                data: {!! json_encode($data) !!},
                fill: false,
                borderColor: 'rgba(34, 197, 94, 1)', // warna hijau tailwind
                tension: 0.3,
                pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                pointRadius: 5,
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        },
        plugins: [arrowPlugin]
    });
</script>

@endsection