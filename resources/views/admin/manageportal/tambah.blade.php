<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Slide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-200 text-gray-800 min-h-screen p-8">
    <h1 class="text-4xl font-bold mb-8 text-center">
        <i class="fa-solid fa-image mr-2 text-blue-600"></i> Tambah Slide
    </h1>

    <form action="{{ route('admin.manageportal.simpan') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto">
        @csrf

        <!-- Nama -->
        <div class="mb-6">
            <label for="nama" class="block mb-2 text-lg font-semibold text-gray-700">Nama Slide</label>
            <input type="text" id="nama" name="nama"
                class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('nama')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Gambar -->
        <div class="mb-6">
            <label for="gambar" class="block mb-2 text-lg font-semibold text-gray-700">Upload Gambar</label>
            <input type="file" id="gambar" name="gambar"
                class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-800 file:bg-blue-600 file:text-white file:border-0 file:px-4 file:py-2 file:rounded hover:file:bg-blue-700">
            @error('gambar')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <button type="submit"
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-lg font-medium transition">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan
            </button>
            <a href="{{ route('admin.manageportal.index') }}"
                class="text-blue-600 hover:underline transition text-lg">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </form>
</body>

</html>
