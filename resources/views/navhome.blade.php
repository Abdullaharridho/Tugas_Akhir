<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/storage/logo1.png">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>

    <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />


    <style>
        [x-cloak] {
            display: none;
        }

        /* Animasi Loading */
        .loading-screen {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.7);
            /* Transparan agar tidak menutupi penuh */
            padding: 20px;
            border-radius: 10px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }
    </style>
</head>

<body class="bg-gray-200 text-white min-h-screen" x-data="{ loading: true }"
    x-init="setTimeout(() => loading = false, getLoadingTime())">

    <!-- Animasi Loading -->
    <div x-show="loading" class="loading-screen">
        <div class="w-12 h-12 border-4 border-t-4 border-blue-500 border-opacity-50 rounded-full animate-spin"></div>
        <p class="mt-2 text-gray-300 text-sm">Loading...</p>
    </div>

    <!-- Navbar -->
    <nav class="bg-gray-300 shadow-lg fixed top-0 w-full z-50 transition-all duration-300 ease-in-out"
        x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <img src="/storage/logo1.png" alt="Logo" class="w-8 h-8 rounded-full">
                    <a href="#" class="text-xl font-bold text-gray-800 hover:text-gray-600 transition-all duration-300">
                       Pondok Pesantren Miftahul Jannah
                    </a>
                </div>

                <!-- Menu Desktop -->
                <div class="hidden md:flex space-x-4">
                    <a href="{{ route('portal.home') }}" class="text-gray-700 hover:text-blue-500 px-3 py-2 transition-all duration-300">Home</a>
                    <a href="{{ route('portal.kegiatan') }}" class="text-gray-700 hover:text-blue-500 px-3 py-2 transition-all duration-300">Kegiatan</a>
                    <a href="{{ route('portal.gallery') }}" class="text-gray-700 hover:text-blue-500 px-3 py-2 transition-all duration-300">Gallery</a>
                    
                    <a href="{{ route('login') }}" class="block text-gray-700 px-4 py-2 hover:bg-gray-200 transition-all duration-300">Login</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" class="focus:outline-none">
                        <svg class="h-6 w-6 text-gray-700 transition-transform duration-300"
                            :class="open ? 'rotate-90' : ''"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" @click.away="open = false" class="md:hidden bg-white shadow-lg absolute w-full transition-all duration-300 ease-in-out"
            x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="-translate-y-full opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="-translate-y-full opacity-0">
            <a href="{{ route('portal.home') }}" class="block text-gray-700 px-4 py-2 hover:bg-gray-200 transition-all duration-300">Home</a>
            <a href="{{ route('portal.kegiatan') }}" class="block text-gray-700 px-4 py-2 hover:bg-gray-200 transition-all duration-300">Kegiatan</a>
            <a href="{{ route('portal.gallery') }}" class="block text-gray-700 px-4 py-2 hover:bg-gray-200 transition-all duration-300">Gallery</a>
            <a href="{{ route('login') }}" class="block text-gray-700 px-4 py-2 hover:bg-gray-200 transition-all duration-300">Login</a>
        </div>
    </nav>

    <!-- Konten Dinamis -->
    <div class="w-full pt-20 pb-8">

    @yield('konten')

    <!-- Section Visi & Misi -->
    
</div>

<!-- Footer -->
<footer class="bg-gray-950 text-gray-300 pt-12 pb-6 px-6 mt-10 shadow-inner">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10">

        <!-- Tentang -->
        <div>
            <h2 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-mosque mr-2 text-blue-400"></i> Pondok Pesantren
            </h2>
            <p class="text-sm leading-relaxed text-gray-400">
                Pondok Pesantren kami berkomitmen mencetak generasi Qurani yang cerdas, mandiri, dan berakhlakul karimah.
                Kami menggabungkan pendidikan agama dan umum dalam lingkungan yang disiplin dan penuh kasih.
            </p>
        </div>

        <!-- Kontak -->
        <div>
            <h2 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-phone-alt mr-2 text-blue-400"></i> Kontak
            </h2>
            <ul class="space-y-2 text-sm text-gray-400">
                <li>
                    <i class="fas fa-envelope text-blue-400 mr-2"></i>
                    <a href="mailto:abdullaharridho03@gmail.com" class="hover:underline">ppmiftahuljannah@gmail.com</a>
                </li>
                <li>
                    <i class="fab fa-whatsapp text-green-400 mr-2"></i>
                    <a href="https://wa.me/6287734197664" target="_blank" class="hover:underline">087734197664</a>
                </li>
            </ul>
        </div>

        <!-- Media Sosial -->
        <div>
            <h2 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-share-alt text-blue-400 mr-2"></i> Media Sosial
            </h2>
            <ul class="space-y-2 text-sm text-gray-400">
                <li>
                    <i class="fab fa-facebook text-blue-600 mr-2"></i>
                    <a href="https://facebook.com/ridho" target="_blank" class="hover:underline">ppmiftahuljannah</a>
                </li>
                <li>
                    <i class="fab fa-instagram text-pink-500 mr-2"></i>
                    <a href="https://instagram.com/ridho5833c" target="_blank" class="hover:underline">ppmiftahuljannah</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="text-center text-gray-500 text-xs mt-10">
        &copy; 2025 Pondok Pesantren. All rights reserved.
    </div>
</footer>



    <!-- Script untuk menentukan durasi loading -->
    <script>
        function getLoadingTime() {
            let connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
            if (connection) {
                let speed = connection.downlink; // Kecepatan unduhan dalam Mbps
                if (speed > 5) return 800; // Koneksi cepat, loading 0.8 detik
                if (speed > 2) return 1500; // Koneksi sedang, loading 1.5 detik
                return 3000; // Koneksi lambat, loading 3 detik
            }
            return 1500; // Default jika tidak bisa mendeteksi koneksi
        }
    </script>

</body>

</html>
