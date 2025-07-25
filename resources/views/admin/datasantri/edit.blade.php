<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/storage/logo1.png">
    <title>Edit Data Santri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6 flex items-center text-gray-800">
            <i class="fas fa-user-edit text-yellow-500 mr-2"></i>Edit Data Santri
        </h1>

        @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('datasantri.update', $santri->nis) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">NIS</label>
                    <input type="text" name="nis" value="{{ old('nis', $santri->nis) }}" readonly
                        class="w-full mt-1 p-2 border border-gray-300 rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $santri->nik) }}"
                        class="w-full mt-1 p-2 border border-gray-300 rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $santri->nama) }}"
                        class="w-full mt-1 p-2 border border-gray-300 rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Tanggal Lahir</label>
                    <input type="date" name="tgllahir" value="{{ old('tgllahir', $santri->tgllahir) }}"
                        class="w-full mt-1 p-2 border border-gray-300 rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full mt-1 p-2 border border-gray-300 rounded">
                        <option value="Laki-laki" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $santri->alamat) }}"
                        class="w-full mt-1 p-2 border border-gray-300 rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium">No HP</label>
                    <input type="text" name="kontak" value="{{ old('kontak', $santri->kontak) }}"
                        class="w-full mt-1 p-2 border border-gray-300 rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Nama Wali</label>
                    <input type="text" name="ortu" value="{{ old('ortu', $santri->ortu) }}"
                        class="w-full mt-1 p-2 border border-gray-300 rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Kelas</label>
                    <select name="kelas" class="w-full mt-1 p-2 border border-gray-300 rounded">
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ old('kelas', $santri->kelas) == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Kamar</label>
                    <select name="kamar" class="w-full mt-1 p-2 border border-gray-300 rounded">
                        @foreach($kamar as $km)
                        <option value="{{ $km->id }}" {{ old('kamar', $santri->kamar) == $km->id ? 'selected' : '' }}>
                            {{ $km->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('datasantri.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</body>

</html>
