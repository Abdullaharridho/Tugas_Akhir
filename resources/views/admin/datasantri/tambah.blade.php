<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-4xl">
        <h2 class="text-3xl font-bold mb-6 text-center text-gray-800 flex items-center justify-center gap-2">
            <i class="fa-solid fa-user-plus text-green-500"></i> Tambah Santri
        </h2>

        <form action="{{ route('datasantri.simpan') }}" method="POST">
            @csrf
            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                <strong class="font-bold">Ups!</strong>
                <span class="block sm:inline">Terdapat kesalahan pada input Anda:</span>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NIK -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">NIK</label>
                    <div class="relative">
                        <input type="text" name="nik" value="{{ old('nik') }}" placeholder="NIK 16 digit"
                            class="border border-gray-300 rounded w-full p-3 pl-10 focus:outline-none focus:ring-2 focus:ring-green-400" required>
                        <i class="fa fa-id-card absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    @error('nik')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Nama</label>
                    <div class="relative">
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap"
                            class="border border-gray-300 rounded w-full p-3 pl-10 focus:outline-none focus:ring-2 focus:ring-green-400" required>
                        <i class="fa fa-user absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Tgl Lahir -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Tgl Lahir</label>
                    <div class="relative">
                        <input type="date" name="tgllahir" value="{{ old('tgllahir') }}"
                            class="border border-gray-300 rounded w-full p-3 pl-10 focus:outline-none focus:ring-2 focus:ring-green-400" required>
                        <i class="fa fa-calendar-alt absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    @error('tgllahir')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Jenis Kelamin</label>
                    <div class="relative">
                        <select name="jenis_kelamin"
                            class="border border-gray-300 rounded w-full p-3 pl-10 appearance-none focus:outline-none focus:ring-2 focus:ring-green-400" required>
                            <option value="" disabled {{ old('jenis_kelamin') == '' ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <i class="fa fa-venus-mars absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Kelas</label>
                    <div class="relative">
                        <select name="kelas"
                            class="border border-gray-300 rounded w-full p-3 pl-10 appearance-none focus:outline-none focus:ring-2 focus:ring-green-400" required>
                            <option value="" disabled selected>Pilih Kelas</option>
                            @foreach ($kelas as $kls)
                            <option value="{{ $kls->id }}" {{ old('kelas') == $kls->id ? 'selected' : '' }}>{{ $kls->nama }}</option>
                            @endforeach
                        </select>
                        <i class="fa fa-school absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Kamar -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Kamar</label>
                    <div class="relative">
                        <select name="kamar"
                            class="border border-gray-300 rounded w-full p-3 pl-10 appearance-none focus:outline-none focus:ring-2 focus:ring-green-400" required>
                            <option value="" disabled selected>Pilih Kamar</option>
                            @foreach ($kamar as $kmr)
                            <option value="{{ $kmr->id }}" {{ old('kamar') == $kmr->id ? 'selected' : '' }}>{{ $kmr->nama }}</option>
                            @endforeach
                        </select>
                        <i class="fa fa-bed absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="mt-6">
                <label class="block text-gray-700 font-medium mb-1">Alamat</label>
                <div class="relative">
                    <textarea name="alamat" placeholder="Alamat lengkap" rows="2"
                        class="border border-gray-300 rounded w-full p-3 pl-10 focus:outline-none focus:ring-2 focus:ring-green-400" required>{{ old('alamat') }}</textarea>
                    <i class="fa fa-map-marker-alt absolute left-3 top-3.5 text-gray-400"></i>
                </div>
            </div>

            <!-- Nama Orang Tua -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Nama Orang Tua</label>
                    <div class="relative">
                        <input type="text" name="ortu" value="{{ old('ortu') }}" placeholder="Nama Ayah/Ibu"
                            class="border border-gray-300 rounded w-full p-3 pl-10 focus:outline-none focus:ring-2 focus:ring-green-400" required>
                        <i class="fa fa-users absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Kontak WhatsApp -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Kontak WhatsApp</label>
                    <div class="relative">
                        <input type="text" name="kontak" value="{{ old('kontak') }}" placeholder="08xxxxxxxxxx"
                            class="border border-gray-300 rounded w-full p-3 pl-10 focus:outline-none focus:ring-2 focus:ring-green-400" required>
                        <i class="fab fa-whatsapp absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Tombol -->
            <div class="mt-8 flex justify-center gap-4">
                <a href="{{ route('datasantri.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded shadow transition">
                    ⬅️ Kembali
                </a>
                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded shadow transition-transform transform hover:scale-105">
                    <i class="fa fa-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>

</body>

</html>