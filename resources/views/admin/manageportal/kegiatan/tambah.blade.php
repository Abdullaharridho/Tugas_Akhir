<!DOCTYPE html>
<html lang="id"> {{-- Ubah ke id untuk konsistensi --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kegiatan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen p-8">

    <h1 class="text-4xl font-bold text-center mb-10">
        <i class="fa-solid fa-calendar-plus text-blue-600 mr-2"></i>Tambah Kegiatan
    </h1>

    <form action="{{ route('admin.manageportal.kegiatan.simpan') }}" method="POST" enctype="multipart/form-data"
        class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-200">
        @csrf

        <!-- Judul -->
        <div class="mb-6">
            <label for="judul" class="block text-lg font-semibold mb-2">Nama Kegiatan</label>
            <input type="text" id="judul" name="judul"
                value="{{ old('judul') }}"
                class="w-full bg-gray-100 border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('judul')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div class="mb-6">
            <label for="deskripsi" class="block text-lg font-semibold mb-2">Deskripsi Kegiatan</label>
            <textarea id="deskripsi" name="deskripsi"
                class="w-full bg-gray-100 border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                rows="4">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Gambar -->
        <div class="mb-6">
            <label for="gambar" class="block text-lg font-semibold mb-2">Upload Gambar</label>
            <input type="file" id="gambar" name="gambar"
                class="w-full bg-white border border-gray-300 px-4 py-2 rounded file:bg-blue-600 file:text-white file:border-0 file:px-4 file:py-2 file:rounded hover:file:bg-blue-700">
            @error('gambar')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-6">
            <a href="{{ route('admin.manageportal.kegiatan.index') }}"
               class="text-blue-600 hover:underline text-lg transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-lg font-medium transition">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>

</body>
</html>
