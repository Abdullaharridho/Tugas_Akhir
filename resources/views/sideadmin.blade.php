<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/storage/logo1.png">
    <title>Management Pondok Pesantren</title>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class'
        };
    </script>

    @if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-transition>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 text-center max-w-sm w-full">
            <h2 class="text-lg font-bold text-green-600 dark:text-green-400 mb-2">Berhasil</h2>
            <p class="text-gray-800 dark:text-gray-200">{{ session('success') }}</p>
            <button @click="show = false"
                class="mt-4 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded">Tutup</button>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-transition>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 text-center max-w-sm w-full">
            <h2 class="text-lg font-bold text-red-600 dark:text-red-400 mb-2">Gagal</h2>
            <p class="text-gray-800 dark:text-gray-200">{{ session('error') }}</p>
            <button @click="show = false"
                class="mt-4 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded">Tutup</button>
        </div>
    </div>
    @endif
</head>

<body x-data="{ open: false, darkMode: localStorage.getItem('dark') === 'true' }"
    x-init="$watch('darkMode', value => { localStorage.setItem('dark', value); document.documentElement.classList.toggle('dark', value); })"
    :class="{ 'dark': darkMode }"
    class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <div :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 w-64 bg-gray-800 dark:bg-gray-700 text-white transform transition-transform duration-300 ease-in-out p-5 z-40 lg:translate-x-0">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-3">
                <img src="/storage/logo1.png" alt="Logo" class="w-8 h-8 rounded-full">
                <span class="text-lg font-semibold">PP Miftahul Jannah</span>
            </div>
            <button @click="open = false" class="text-white lg:hidden">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <nav class="space-y-1">
            <a href="{{ route('admin.dashboard') }}"
                class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                <i class="fa-solid fa-house mr-2"></i> Home
            </a>

            <div x-data="{ open: {{ request()->routeIs('admin.manageportal.*') ? 'true' : 'false' }} }" class="relative">
                <a href="#" @click.prevent="open = !open"
                    class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('admin.manageportal.*') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                    <i class="fa-solid fa-gear mr-2"></i> Management Portal+
                </a>
                <div x-show="open" @click.away="open = false" class="ml-4 mt-1 space-y-1">
                    <a href="{{ route('admin.manageportal.index') }}"
                        class="block py-2 px-4 rounded hover:bg-gray-600 {{ request()->routeIs('admin.manageportal.index') ? 'bg-gray-600 font-bold border-l-4 border-blue-400' : '' }}">
                        <i class="fa-solid fa-sliders mr-2"></i> Slide
                    </a>
                    <a href="{{ route('admin.manageportal.gallery.index') }}"
                        class="block py-2 px-4 rounded hover:bg-gray-600 {{ request()->routeIs('admin.manageportal.gallery.index') ? 'bg-gray-600 font-bold border-l-4 border-blue-400' : '' }}">
                        <i class="fa-solid fa-image mr-2"></i> Gallery
                    </a>
                    <a href="{{ route('admin.manageportal.kegiatan.index') }}"
                        class="block py-2 px-4 rounded hover:bg-gray-600 {{ request()->routeIs('admin.manageportal.kegiatan.index') ? 'bg-gray-600 font-bold border-l-4 border-blue-400' : '' }}">
                        <i class="fa-solid fa-calendar-days mr-2"></i> Kegiatan
                    </a>
                </div>
            </div>

            <a href="{{ route('guru.index') }}"
                class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('guru.*') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                <i class="fa-solid fa-user-check mr-2"></i> Userguru
            </a>

            <a href="{{ route('datasantri.index') }}"
                class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('datasantri.*') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                <i class="fa-solid fa-users mr-2"></i> Data Santri
            </a>

            <a href="{{ route('kelas.index') }}"
                class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('kelas.*') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                <i class="fa-solid fa-chalkboard-user mr-2"></i> Data Kelas
            </a>

            <a href="{{ route('mapel.index') }}"
                class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('mapel.*') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                <i class="fa-solid fa-book-open mr-2"></i> Mata Pelajaran
            </a>

            <a href="{{ route('kamar.index') }}"
                class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('kamar.*') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                <i class="fa-solid fa-bed mr-2"></i> Kamar
            </a>

            <a href="{{ route('pelanggaran.index') }}"
                class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('pelanggaran.*') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i> Pelanggaran
            </a>

            <a href="{{ route('tabungan.index') }}"
                class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('tabungan.*') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                <i class="fa-solid fa-piggy-bank mr-2"></i> Tabungan
            </a>

            <a href="{{ route('perizinan.tampil') }}"
                class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('perizinan.*') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                <i class="fa-solid fa-door-open mr-2"></i> Perizinan
            </a>

            <a href="{{ route('useradmin.index') }}"
                class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('useradmin.index') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                <i class="fa-solid fa-user-shield mr-2"></i> Pengurus
            </a>

            <a href="{{ route('useradmin.password.form') }}"
                class="block py-2 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('useradmin.password.form') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                <i class="fa-solid fa-key mr-2"></i> Ubah Password
            </a>
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="button"
                @click="if(confirm('Apakah Anda yakin ingin logout?')) $el.closest('form').submit()"
                class="block py-2 px-4 bg-red-500 hover:bg-red-700 rounded w-full text-center">
                <i class="fa-solid fa-right-from-bracket mr-2"></i> Log Out
            </button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="flex-1 min-h-screen flex flex-col">
        <!-- Tombol Dark Mode -->
        <button @click="darkMode = !darkMode"
            class="fixed top-4 right-4 bg-blue-500 dark:bg-yellow-400 text-white dark:text-black p-2 rounded transition-transform duration-300 hover:scale-110 z-50">
            <span x-show="!darkMode">🌙</span>
            <span x-show="darkMode">☀️</span>
        </button>

        <!-- Main Content -->
        <main class="min-h-screen p-6 bg-gray-100 dark:bg-gray-900 lg:ml-64">
            <!-- Toggle Sidebar -->
            <div class="lg:hidden mb-4">
                <button @click="open = !open"
                    class="p-2 bg-gray-800 text-white rounded-md shadow hover:scale-105 transition">
                    <i class="fas fa-bars"></i> Menu
                </button>
            </div>

            <!-- Konten dinamis -->
            <div class="w-full">
                @yield('konten')
            </div>
        </main>
    </div>

    <!-- Tombol Home -->
    <a href="{{ route('admin.dashboard') }}"
        class="fixed bottom-4 right-4 bg-black dark:bg-white text-white dark:text-black p-2 rounded-full shadow-lg hover:scale-110 transition-transform">
        <i class="fas fa-home text-lg"></i>
    </a>

    @stack('scripts')
</body>

</html>