<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
</head>
<body x-data="{ darkMode: localStorage.getItem('dark') === 'true' }"
      x-init="$watch('darkMode', value => { localStorage.setItem('dark', value); document.documentElement.classList.toggle('dark', value); })"
      :class="{ 'dark': darkMode }"
      class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-white flex items-center justify-center p-6">

    <!-- Dark mode toggle -->
    <button @click="darkMode = !darkMode"
            class="fixed top-4 right-4 bg-blue-600 dark:bg-yellow-300 text-white dark:text-black p-2 rounded-full shadow-lg transition hover:scale-110 z-50">
        <i x-show="!darkMode" class="fas fa-moon"></i>
        <i x-show="darkMode" class="fas fa-sun"></i>
    </button>

    <!-- Form Container -->
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)"
         x-show="show"
         x-transition.duration.500ms
         class="w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 transform transition-all duration-700 ease-in-out scale-95">

        <h2 class="text-2xl font-bold mb-4 text-center text-gray-800 dark:text-white flex items-center justify-center gap-2">
            <i class="fas fa-key"></i> Ubah Password
        </h2>

       

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('guru.password.update') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block font-semibold text-sm mb-1">
                    <i class="fas fa-lock mr-1"></i> Password Lama
                </label>
                <input type="password" name="password_lama" required
                       class="w-full p-2 rounded-lg border dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label class="block font-semibold text-sm mb-1">
                    <i class="fas fa-lock-open mr-1"></i> Password Baru
                </label>
                <input type="password" name="password_baru" required
                       class="w-full p-2 rounded-lg border dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label class="block font-semibold text-sm mb-1">
                    <i class="fas fa-lock mr-1"></i> Konfirmasi Password Baru
                </label>
                <input type="password" name="password_baru_confirmation" required
                       class="w-full p-2 rounded-lg border dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-300">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition transform hover:scale-105 flex justify-center items-center gap-2">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</body>
</html>
