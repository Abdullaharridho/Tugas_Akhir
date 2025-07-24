<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/storage/logo1.png">


    <title>Management Absensi Madin</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    @if (session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        x-transition>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 text-center max-w-sm w-full">
            <h2 class="text-lg font-bold text-green-600 dark:text-green-400 mb-2">Berhasil</h2>
            <p class="text-gray-800 dark:text-gray-200">{{ session('success') }}</p>
            <button
                @click="show = false"
                class="mt-4 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded">
                Tutup
            </button>
        </div>
    </div>
    @endif
</head>

<body x-data="{ open: false, darkMode: localStorage.getItem('dark') === 'true' }"
    x-init="$watch('darkMode', value => { localStorage.setItem('dark', value); document.documentElement.classList.toggle('dark', value); })"
    :class="{ 'dark': darkMode }" class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen">

    <!-- Dark Mode Toggle -->
    <button @click="darkMode = !darkMode"
        class="fixed top-4 right-4 z-50 bg-blue-600 dark:bg-yellow-300 text-white dark:text-black p-2 rounded-full shadow-lg transition hover:scale-110">
        <i x-show="!darkMode" class="fas fa-moon"></i>
        <i x-show="darkMode" class="fas fa-sun"></i>
    </button>

    <div class="flex">
        <!-- Sidebar -->
        <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 w-64 bg-gray-800 dark:bg-gray-700 text-white transform transition-transform duration-300 ease-in-out z-40 p-5 lg:translate-x-0">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold"><i class="fas fa-user-shield mr-2"></i>Dashboard Guru</h2>
                <button @click="open = false" class="lg:hidden text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="space-y-2">
                <a href="{{ route('userguru.tampil') }}"
                    class="flex items-center gap-2 py-2 px-4 hover:bg-gray-700 rounded transition
        {{ request()->routeIs('userguru.tampil') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-home"></i> <span>Beranda</span>
                </a>

                <div x-data="{ open: {{ request()->routeIs('absensi.*') ? 'true' : 'false' }} }">
                    <a href="#" @click.prevent="open = !open"
                        class="flex items-center justify-between py-2 px-4 hover:bg-gray-700 rounded transition
            {{ request()->routeIs('absensi.*') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-clipboard-check"></i> Absensi
                        </span>
                        <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </a>
                    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1">
                        <a href="{{ route('absensi.index') }}"
                            class="block py-2 px-4 rounded hover:bg-gray-600
                {{ request()->routeIs('absensi.index') ? 'bg-gray-600 font-bold border-l-4 border-blue-400' : '' }}">
                            <i class="fas fa-calendar-check mr-2"></i> Daftar Absensi
                        </a>
                        {{-- Tambah item lain jika diperlukan --}}
                        {{-- <a href="#" class="block py-2 px-4 rounded hover:bg-gray-600">Rekap Absensi</a> --}}
                    </div>
                </div>

                <a href="{{ route('guru.password.form') }}"
                    class="flex items-center gap-2 py-2 px-4 hover:bg-gray-700 rounded transition
        {{ request()->routeIs('guru.password.form') ? 'bg-gray-700 font-bold border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-key"></i> <span>Ubah Password</span>
                </a>
            </nav>

            <!-- Logout dengan Konfirmasi -->
            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="button"
                    @click="if(confirm('Apakah Anda yakin ingin logout?')) $el.closest('form').submit()"
                    class="w-full flex items-center justify-center gap-2 py-2 px-4 bg-red-500 hover:bg-red-600 rounded transition text-white">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-h-screen p-6 lg:ml-64">
            <!-- Toggle Sidebar -->
            <button @click="open = !open"
                class="p-2 bg-gray-800 text-white rounded-md mb-4 lg:hidden shadow hover:scale-105 transition">
                <i class="fas fa-bars"></i> Menu
            </button>

            <div>
                @yield('konten')
            </div>
        </main>
    </div>

    <!-- Home Floating Button -->
    <a href="{{ route('admin.dashboard') }}"
        class="fixed bottom-4 right-4 z-50 bg-black dark:bg-white text-white dark:text-black p-3 rounded-full shadow-lg hover:scale-110 transition">
        <i class="fas fa-house"></i>
    </a>
    @stack('scripts')
</body>

</html>