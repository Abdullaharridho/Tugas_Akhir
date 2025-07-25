<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/storage/logo1.png">
    <title>Edit Kelas</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Edit Kelas</h2>

        <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
            @csrf @method('PUT')

            <label class="block">
                <span class="text-gray-700">Nama Kelas:</span>
                <input type="text" name="nama" class="block w-full mt-1 p-2 border rounded" value="{{ $kelas->nama }}" required>
            </label>

            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('kelas.index') }}" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
        </form>
    </div>

</body>

</html>