<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar with Alpine.js & Tailwind</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div x-data="{ open: false }" class="flex">
        <!-- Sidebar -->
        <div :class="open ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white transform transition-transform duration-300 ease-in-out p-5 lg:translate-x-0">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Dashboard</h2>
                <button @click="open = false" class="text-white lg:hidden">
                    <i class="fa-solid fa-xmark"></i> <!-- Ikon Close -->
                </button>
            </div>
            <nav class="space-y-2">
                <a href="#" class="flex items-center py-2 px-4 hover:bg-gray-700 rounded">
                    <i class="fa-solid fa-house mr-3"></i> Home
                </a>
                <a href="{{ route('admin.manageportal.index') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 rounded">
                    <i class="fa-solid fa-image mr-3"></i> Slide
                </a>
                <a href="{{ route('admin.manageportal.gallery.index') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 rounded">
                    <i class="fa-solid fa-photo-film mr-3"></i> Gallery
                </a>
                <a href="{{ route('admin.manageportal.kegiatan.index') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 rounded">
                    <i class="fa-solid fa-calendar-days mr-3"></i> Kegiatan
                </a>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 rounded">
                    <i class="fa-solid fa-arrow-left mr-3"></i> Kembali
                </a>
            </nav>
        </div>
        
        <!-- Content -->
        <div class="flex-1 min-h-screen p-6 lg:ml-64">
            <button @click="open = !open" class="p-2 bg-gray-800 text-white rounded-md mb-4 lg:hidden">
                <i class="fa-solid fa-bars"></i> Toggle Sidebar
            </button>
            <div>
                @yield('konten')
            </div>
        </div>
    </div>
</body>
</html>
