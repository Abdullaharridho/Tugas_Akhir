<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Kirim OTP</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-r from-blue-50 to-blue-100 min-h-screen flex items-center justify-center px-4">

    <div 
        x-data="{ show: false }" 
        x-init="setTimeout(() => show = true, 200)" 
        x-show="show" 
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="w-full max-w-md bg-white shadow-xl rounded-xl p-6 space-y-5"
    >
        <form method="POST" action="{{ route('password.otp.send') }}">
            @csrf

            <h2 class="text-2xl font-bold text-center text-blue-600">Reset Password</h2>

            @if ($errors->any())
                <div 
                    x-data="{ showError: true }" 
                    x-show="showError" 
                    x-transition 
                    class="bg-red-100 text-red-700 p-3 rounded-md"
                >
                    <ul class="text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-col space-y-1">
                <label for="email" class="text-sm text-gray-600">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    required 
                    placeholder="Masukkan email Anda"
                    class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                />
            </div>

            <button 
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition transform hover:scale-105 duration-300"
            >
                Kirim OTP
            </button>

            <p class="text-xs text-center text-gray-400">Kode OTP akan dikirimkan ke email Anda</p>
        </form>
    </div>

</body>
</html>
