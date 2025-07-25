<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/storage/logo1.png">
    <title>Edit Slide</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen p-6">

    <h1 class="text-4xl font-bold text-center mb-10">
        <i class="fa-solid fa-pen-to-square mr-2 text-yellow-500"></i> Edit Slide
    </h1>

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-200">
        <form action="{{ route('slide.update', $slide->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <label class="block text-lg font-semibold mb-2">Nama Slide</label>
            <input type="text" name="nama" value="{{ $slide->nama }}"
                class="w-full bg-gray-100 border border-gray-300 px-4 py-2 rounded mb-6 focus:outline-none focus:ring-2 focus:ring-yellow-500">

            <!-- Gambar Lama -->
            <label class="block text-lg font-semibold mb-2">Gambar Saat Ini</label>
            <img src="{{ asset('storage/' . $slide->gambar) }}"
                 class="w-40 h-24 object-cover rounded shadow mb-6 border border-gray-300">

            <!-- Upload Gambar Baru -->
            <label class="block text-lg font-semibold mb-2">Upload Gambar Baru (Opsional)</label>
            <input type="file" name="gambar"
                class="w-full bg-white border border-gray-300 px-4 py-2 rounded mb-6 file:bg-yellow-500 file:text-white file:border-0 file:px-4 file:py-2 file:rounded hover:file:bg-yellow-600">

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <a href="{{ route('admin.manageportal.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2 rounded-lg font-medium transition">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    <i class="fa-solid fa-check mr-2"></i> Update Slide
                </button>
            </div>
        </form>
    </div>

</body>
</html>
