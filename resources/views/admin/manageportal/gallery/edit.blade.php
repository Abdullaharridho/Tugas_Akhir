<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/storage/logo1.png">
    <title>Edit Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen p-8">

    <h1 class="text-4xl font-bold text-center mb-10">
        <i class="fa-solid fa-pen-to-square text-yellow-500 mr-2"></i> Edit Gallery
    </h1>

    <form action="{{ route('gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data"
          class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-200">
        @csrf
        @method('PUT')

        <!-- Nama Gallery -->
        <div class="mb-6">
            <label for="nama" class="block text-lg font-semibold mb-2">Nama Gallery</label>
            <input type="text" id="nama" name="nama" value="{{ $gallery->nama }}"
                   class="w-full bg-gray-100 border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Deskripsi -->
        <div class="mb-6">
            <label for="deskripsi" class="block text-lg font-semibold mb-2">Deskripsi</label>
            <input type="text" id="deskripsi" name="deskripsi" value="{{ $gallery->deskripsi }}"
                   class="w-full bg-gray-100 border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Gambar Saat Ini -->
        <div class="mb-6">
            <label class="block text-lg font-semibold mb-2">Gambar Saat Ini</label>
            <img src="{{ asset('storage/' . $gallery->gambar) }}" alt="Current Image"
                 class="w-36 h-24 object-cover rounded shadow-md">
        </div>

        <!-- Upload Gambar Baru -->
        <div class="mb-6">
            <label for="gambar" class="block text-lg font-semibold mb-2">Upload Gambar Baru (Opsional)</label>
            <input type="file" id="gambar" name="gambar"
                   class="w-full bg-white border border-gray-300 px-4 py-2 rounded file:bg-blue-600 file:text-white file:border-0 file:px-4 file:py-2 file:rounded hover:file:bg-blue-700">
        </div>

        <!-- Tombol Aksi -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-6">
            <a href="{{ route('admin.manageportal.gallery.index') }}"
               class="text-blue-600 hover:underline text-lg transition">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
            </a>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-lg font-medium transition">
                <i class="fa-solid fa-save mr-2"></i> Update
            </button>
        </div>
    </form>

</body>
</html>
