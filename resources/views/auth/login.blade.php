<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/storage/logo1.png">
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
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

<body class="min-h-screen flex bg-gray-100" x-data="{ showError: {{ session('error') ? 'true' : 'false' }} }">

    <!-- Form Login - 30% -->
    <div class="w-full md:w-[30%] flex items-center justify-center bg-white shadow-lg z-10">
        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-show="show"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 -translate-x-10"
            x-transition:enter-end="opacity-100 translate-x-0" class="w-full max-w-sm p-8">
            <div class="text-center mb-4">
                <div class="flex items-center justify-center space-x-3">
                    <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="h-20 w-20 object-cover">
                    <h1 class="text-xl font-semibold text-gray-700">
                        Pondok Pesantren Miftahul Jannah
                    </h1>
                </div>
            </div>
            <div class="mb-6 text-center">
                <p class="text-gray-500 mt-1 text-sm">Selamat datang kembali</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Input Email/NIS -->
                <div>
                    <label for="identifier" class="block text-sm font-medium text-gray-600 mb-1">Email atau NIS</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M16 12a4 4 0 10-8 0 4 4 0 008 0z" />
                                <path d="M12 14v6m0-6a8 8 0 018-8 8 8 0 00-16 0 8 8 0 018 8z" />
                            </svg>
                        </span>
                        <input id="identifier" name="identifier" type="text" required autofocus
                            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                            placeholder="Masukkan Email atau NIS">
                    </div>
                    @error('identifier')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M12 15v2m0-6a4 4 0 014 4H8a4 4 0 014-4z" />
                                <path d="M16 15V9a4 4 0 10-8 0v6" />
                            </svg>
                        </span>
                        <input id="password" name="password" type="password" required
                            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                            placeholder="Masukkan Password">
                    </div>
                    @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div x-data="{ showInfo: false }" class="inline-flex items-center gap-2 text-blue-600">
                    <!-- Link tetap seperti biasa -->
                    <a href="{{ route('password.reques') }}" class="hover:underline inline-flex items-center gap-1">
                        <i class="fa-solid fa-key"></i> Lupa Password
                    </a>

                    <!-- Ikon tanda tanya -->
                    <div class="relative">
                        <i class="fa-solid fa-circle-question text-gray-400 hover:text-gray-600 cursor-pointer"
                            @click="showInfo = !showInfo"></i>

                        <!-- Teks muncul di samping ikon -->
                        <div x-show="showInfo"
                            x-transition
                            @click.away="showInfo = false"
                            class="absolute left-full ml-2 top-1/2 -translate-y-1/2 bg-white border border-gray-300 shadow px-3 py-1 text-sm text-gray-700 rounded-md z-10 whitespace-nowrap">
                            Fitur ini hanya tersedia untuk Pengurus dan Guru.
                        </div>
                    </div>
                </div>


                <!-- Tombol Login -->
                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow transition duration-300 transform hover:scale-105">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Background - 70% -->
    <div class="hidden md:block md:w-[70%] bg-gray-500 bg-cover bg-center"
        style="background-image: url('{{ asset('storage/bglogin.jpg') }}');">
    </div>

    <!-- Modal Login Gagal -->
    <div x-show="showError" x-transition
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl w-80 text-center">
            <h2 class="text-red-600 text-xl font-semibold mb-2">Login Gagal</h2>
            <p class="text-gray-600 mb-4">{{ session('error') }}</p>
            <button @click="showError = false"
                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Oke</button>
        </div>
    </div>

</body>

</html>