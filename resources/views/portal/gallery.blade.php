@extends('navhome')

@section('konten')

<!-- Tambahkan Alpine.js jika belum ada -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="pt-24 pb-20 bg-gradient-to-br from-gray-100 via-white to-gray-200 text-gray-800 min-h-screen w-full" 
     x-data 
     x-init="
        let cards = document.querySelectorAll('[x-gallery]');
        cards.forEach((el, i) => {
            setTimeout(() => {
                el.classList.remove('opacity-0', 'translate-y-5');
                el.classList.add('opacity-100', 'translate-y-0');
            }, i * 150); // Delay 150ms per item
        });
     ">

    <!-- Judul -->
    <div class="text-center mb-16">
        <h2 class="text-5xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-500 to-cyan-500 text-transparent bg-clip-text drop-shadow-md">
            Galeri Pesantren
        </h2>
        <p class="mt-4 text-lg text-gray-600">Momen terbaik dan kenangan santri dalam satu tempat</p>
    </div>

    <!-- Grid Galeri -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 px-6 sm:px-8 md:px-12 lg:px-20">
        @foreach($galleries as $gallery)
        <div 
            x-gallery 
            class="relative group rounded-3xl overflow-hidden shadow-lg bg-white transition-all duration-500 hover:shadow-2xl hover:scale-105 opacity-0 translate-y-5"
        >
            <!-- Gambar -->
            <img src="{{ asset('storage/' . $gallery->gambar) }}"
                 class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out"
                 alt="{{ $gallery->nama }}">

            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-5 text-white">
                <h3 class="text-2xl font-semibold mb-1">{{ $gallery->nama }}</h3>
                <p class="text-sm text-gray-200">{{ $gallery->deskripsi }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
