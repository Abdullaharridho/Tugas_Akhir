@extends('portal')

@section('konten')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Dashboard</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Gallery Count -->
        <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md flex items-center">
            <i class="fa-solid fa-photo-film text-4xl mr-4"></i>
            <div>
                <h3 class="text-lg font-semibold">Gallery</h3>
                <p class="text-2xl font-bold">{{ $galleryCount }}</p>
            </div>
        </div>

        <!-- Kegiatan Count -->
        <div class="bg-green-500 text-white p-6 rounded-lg shadow-md flex items-center">
            <i class="fa-solid fa-calendar-days text-4xl mr-4"></i>
            <div>
                <h3 class="text-lg font-semibold">Kegiatan</h3>
                <p class="text-2xl font-bold">{{ $kegiatanCount }}</p>
            </div>
        </div>

        <!-- Slide Count -->
        <div class="bg-purple-500 text-white p-6 rounded-lg shadow-md flex items-center">
            <i class="fa-solid fa-images text-4xl mr-4"></i>
            <div>
                <h3 class="text-lg font-semibold">Slides</h3>
                <p class="text-2xl font-bold">{{ $slideCount }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
