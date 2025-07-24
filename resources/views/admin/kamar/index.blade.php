@extends('sideadmin')

@section('konten')
<div x-data="kamarModal()" class="container mx-auto p-6">

    <!-- Modal Tambah -->
    <div x-show="showTambah" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div @click.outside="showTambah = false" class="bg-white p-6 rounded-xl w-full max-w-md shadow-lg">
            <h2 class="text-xl font-bold mb-4">Tambah Kamar</h2>
            <form action="{{ route('kamar.simpan') }}" method="POST">
                @csrf
                <label class="block mb-4">
                    <span class="text-gray-700">Nama Kamar:</span>
                    <input type="text" name="nama" class="block w-full mt-1 p-2 border rounded" required>
                </label>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showTambah = false" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="showEdit" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div @click.outside="showEdit = false" class="bg-white p-6 rounded-xl w-full max-w-md shadow-lg">
            <h2 class="text-xl font-bold mb-4">Edit Kamar</h2>
            <form :action="editUrl" method="POST">
                @csrf
                @method('PUT')
                <label class="block mb-4">
                    <span class="text-gray-700">Nama Kamar:</span>
                    <input type="text" name="nama" x-model="editData.nama" class="block w-full mt-1 p-2 border rounded" required>
                </label>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showEdit = false" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Konten Utama -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">🏠 Daftar Kamar</h2>
            <button @click="showTambah = true"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-full shadow transition">
                ➕ Tambah Kamar
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">No</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Nama Kamar</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                    @forelse($kamar as $k => $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4">{{ $k + 1 }}</td>
                        <td class="px-6 py-4">{{ $item->nama }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <button @click="openEdit({{ $item->id }}, '{{ $item->nama }}')"
                                    class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-1.5 rounded-full font-medium transition">
                                    ✏️ Edit
                                </button>
                                <form action="{{ route('kamar.hapus', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus kamar ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-full font-medium transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center px-6 py-6 text-gray-500 dark:text-gray-400">
                            Belum ada data kamar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function kamarModal() {
    return {
        showTambah: false,
        showEdit: false,
        editData: {
            id: '',
            nama: '',
        },
        get editUrl() {
            return `/kamar/${this.editData.id}`;
        },
        openEdit(id, nama) {
            this.editData.id = id;
            this.editData.nama = nama;
            this.showEdit = true;
        },
    }
}
</script>
@endsection
