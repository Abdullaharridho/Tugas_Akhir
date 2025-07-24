@extends('navhome')

@section('konten')
<div class="bg-gray-100 text-gray-800 w-full" x-data="{ fadeIn: false }"
    x-init="setTimeout(() => fadeIn = true, 200)"
    x-bind:class="{ 'opacity-100 transition-opacity duration-1000': fadeIn, 'opacity-0': !fadeIn }">

    <!-- SLIDER -->
    <div class="relative w-full h-64 md:h-[28rem] overflow-hidden"
        x-data="{
            currentSlide: 0,
            slides: {{ json_encode($slides->pluck('gambar')) }},
            start() { setInterval(() => this.next(), 5000); },
            next() { this.currentSlide = (this.currentSlide + 1) % this.slides.length; }
        }" x-init="start()">

        <template x-for="(slide, index) in slides" :key="index">
            <div
                x-show="currentSlide === index"
                x-transition:enter="transition duration-1000 ease-in-out"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition duration-1000 ease-in-out"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute inset-0">
                <img :src="'{{ asset('storage') }}/' + slide"
                    class="w-full h-full object-cover brightness-75 transition-all duration-1000 ease-in-out" />
                <div class="absolute inset-0 bg-emerald-900/40 flex items-center justify-center">
                    <h2 class="text-3xl md:text-5xl font-bold text-white text-center drop-shadow-lg">
                        Selamat Datang di Pondok Pesantren
                    </h2>
                </div>
            </div>
        </template>
    </div>

    <!-- GALLERY -->
    <section class="py-16 bg-gray-100 w-full">
        <h2 class="text-3xl font-bold text-center text-emerald-600 mb-10">Galeri</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 w-full px-4">
            @foreach($galleries->take(6) as $gallery)
            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                <img src="{{ asset('storage/' . $gallery->gambar) }}" class="h-52 w-full object-cover" alt="{{ $gallery->nama }}">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $gallery->nama }}</h3>
                    <p class="text-gray-600 text-sm mt-1">{{ $gallery->deskripsi }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('portal.gallery') }}" class="inline-block bg-emerald-600 text-white px-6 py-2 rounded hover:bg-emerald-700 transition">
                <i class="fas fa-images mr-2"></i> Lihat Selengkapnya
            </a>
        </div>
    </section>

    <!-- KEGIATAN -->
    <section class="py-16 bg-white w-full">
    <h2 class="text-3xl font-bold text-center text-emerald-600 mb-10">Kegiatan</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 w-full px-4">
        @foreach($kegiatan->take(6) as $item)
        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
            <img src="{{ asset('storage/' . $item->gambar) }}" class="h-52 w-full object-cover" alt="{{ $item->judul }}">
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-800">{{ $item->judul }}</h3>
                <p class="text-gray-600 text-sm mt-1">{{ $item->deskripsi }}</p>
            </div>
        </div>
        @endforeach
    </div>
    <div class="text-center mt-8">
        <a href="{{ route('portal.kegiatan') }}" class="inline-block bg-emerald-600 text-white px-6 py-2 rounded hover:bg-emerald-700 transition">
            <i class="fas fa-calendar-alt mr-2"></i> Lihat Selengkapnya
        </a>
    </div>
</section>

    <!-- VISI MISI -->
    <section class="py-16 bg-gradient-to-t from-emerald-800 via-emerald-500 to-white text-white w-full">
        <h2 class="text-3xl font-bold text-center text-white mb-10 drop-shadow-lg">Visi & Misi</h2>
        <div class="grid md:grid-cols-2 gap-12 w-full px-4">
            <!-- Visi -->
            <div>
                <h3 class="text-2xl font-semibold mb-3">Visi</h3>
                <p class="leading-relaxed">
                    Menjadi pondok pesantren unggulan dalam mencetak generasi Qurani yang berakhlak mulia, cerdas, dan berdaya saing global.
                </p>
            </div>
            <!-- Misi -->
            <div>
                <h3 class="text-2xl font-semibold mb-3">Misi</h3>
                <ul class="list-disc list-inside space-y-2 text-left">
                    <li>Mengintegrasikan ilmu agama dan ilmu umum dalam kurikulum pembelajaran.</li>
                    <li>Membangun karakter santri yang disiplin, mandiri, dan bertanggung jawab.</li>
                    <li>Menanamkan nilai-nilai akhlakul karimah dalam kehidupan sehari-hari.</li>
                    <li>Meningkatkan literasi digital dan kompetensi abad 21.</li>
                </ul>
            </div>
        </div>
    </section>

</div>
@endsection