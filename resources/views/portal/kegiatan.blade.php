@extends('navhome')

@section('konten')

<!-- Alpine.js (jika belum dimuat di layout) -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="pt-24 pb-20 bg-gradient-to-b from-white via-gray-100 to-gray-200 text-gray-800 min-h-screen w-full" x-data x-init="
    let cards = document.querySelectorAll('[x-fade]');
    cards.forEach((el, i) => {
        setTimeout(() => {
            el.classList.remove('opacity-0', 'translate-y-4');
            el.classList.add('opacity-100', 'translate-y-0');
        }, i * 200);
    });
">

    <!-- Judul -->
    <div class="text-center mb-12">
        <h2 class="text-4xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-500 to-sky-500 text-transparent bg-clip-text drop-shadow-md">
            Kegiatan Pesantren
        </h2>
        <p class="mt-2 text-gray-600 text-lg">Dokumentasi berbagai aktivitas dan program pesantren</p>
    </div>

    <!-- Grid Kegiatan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 px-6 sm:px-8 md:px-12 lg:px-20">
        @foreach($kegiatan as $item)
        <div
            class="bg-white rounded-3xl overflow-hidden shadow-lg group hover:shadow-2xl hover:scale-[1.03] transition duration-500 opacity-0 translate-y-4"
            x-fade
        >
            <!-- Gambar -->
            <img src="{{ asset('storage/' . $item->gambar) }}"
                 class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-700 ease-in-out"
                 alt="{{ $item->judul }}">

            <!-- Konten -->
            <div class="p-5 bg-gray-50">
                <h5 class="text-xl font-semibold text-gray-800 mb-2 text-center group-hover:text-indigo-600 transition-colors duration-300">
                    {{ $item->judul }}
                </h5>
                <p class="text-sm text-gray-600 text-justify leading-relaxed">
                    {{ $item->deskripsi }}
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
