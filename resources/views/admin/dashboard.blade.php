@extends('sideadmin')

@section('konten')
@if (session('error'))
    <div class="bg-red-500 text-white text-sm p-3 rounded mb-4 shadow-md">
        {{ session('error') }}
    </div>
@endif


<div class="max-w-6xl mx-auto p-6" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
    <div class="mb-6 text-center" x-show="show" x-transition.opacity.duration.1000ms>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Selamat datang, {{ Auth::user()->name }} 👋
        </h1>
        <p class="text-gray-600 dark:text-gray-300">Senang melihatmu kembali di dashboard admin!</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @php 
            $cards = [
                ['title' => 'Jumlah Santri', 'icon' => 'fa-users', 'value' => $totalSantri, 'color' => 'from-blue-500 to-blue-600'],
                ['title' => 'Jumlah Kelas', 'icon' => 'fa-chalkboard-teacher', 'value' => $totalKelas, 'color' => 'from-green-500 to-green-600'],
                ['title' => 'Jumlah Kamar', 'icon' => 'fa-bed', 'value' => $totalKamar, 'color' => 'from-yellow-500 to-yellow-600'],
                ['title' => 'Santri yang Izin', 'icon' => 'fa-user-clock', 'value' => $totalIzin, 'color' => 'from-red-500 to-red-600'],
            ]; 
        @endphp

        @foreach ($cards as $i => $card)
        <div 
            class="bg-gradient-to-r {{ $card['color'] }} text-white p-6 rounded-2xl shadow-xl transform hover:scale-105 transition duration-500 ease-in-out"
            x-show="show"
            x-transition.opacity.duration.700ms.delay.{{ $i * 200 }}ms
        >
            <div class="flex items-center space-x-4">
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fa-solid {{ $card['icon'] }} text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium">{{ $card['title'] }}</h3>
                    <p class="text-3xl font-bold">{{ $card['value'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6" x-show="show" x-transition.opacity.duration.1000ms>
    <!-- GRAFIK -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xl md:col-span-2">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">
            Grafik Santri Baru per Bulan ({{ now()->year }})
        </h2>
        <canvas id="chartSantri"></canvas>
    </div>

    <!-- LIST PENGURUS -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xl">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Daftar Pengurus</h2>
        <ul class="space-y-3">
            @forelse ($pengurus as $p)
                <li class="flex items-center justify-between border-b pb-2">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-white">{{ $p->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $p->email }}</p>
                    </div>
                </li>
            @empty
                <li class="text-gray-500 dark:text-gray-400">Tidak ada pengurus ditemukan.</li>
            @endforelse
        </ul>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartSantri').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Santri Baru',
                data: @json($dataBulanan),
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
</div>
@endsection
