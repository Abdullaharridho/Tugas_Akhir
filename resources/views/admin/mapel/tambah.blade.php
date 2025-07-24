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

<body>
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Tambah Mata Pelajaran</h2>

        <form action="{{ route('mapel.simpan') }}" method="POST">
            @csrf
            <label class="block">
                <span class="text-gray-700">Nama Mata Pelajaran:</span>
                <input type="text" name="nama" class="block w-full mt-1 p-2 border rounded" required>
            </label>

            <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('mapel.index') }}" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
        </form>
    </div>
</body>

</html>