@extends('sideadmin')

@section('konten')
<div x-data="modalHandler()" class="container mx-auto p-6">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
        <h2 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white text-center">💰 Riwayat Transaksi Tabungan</h2>

        <div class="overflow-x-auto rounded-lg shadow-sm">
            <table class="w-full min-w-max table-auto divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">NIS</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Nama Santri</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Tanggal</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Jenis</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Jumlah</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Pengurus</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800">
                    @forelse($transaksi as $t)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-4 py-3">{{ $t->nis }}</td>
                        <td class="px-4 py-3">{{ $santri->nama }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-sm font-medium
                                {{ $t->jenis == 'tabung' ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-100' }}">
                                {{ ucfirst($t->jenis) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $t->pengurus->name ?? '-' }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            <button @click="edit({{ $t->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Edit</button>
                            <form action="{{ route('tabungan.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center px-4 py-6 text-gray-500 dark:text-gray-400">
                            Tidak ada data transaksi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="close()" class="bg-white dark:bg-gray-900 rounded-lg p-6 w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Edit Transaksi</h2>
            <form :action="`/tabungan/${form.id}`" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                    <input type="date" name="tanggal" x-model="form.tanggal" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis</label>
                    <select name="jenis" x-model="form.jenis" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="tabung">Tabung</option>
                        <option value="ambil">Ambil</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah</label>
                    <input type="number" name="jumlah" x-model="form.jumlah" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="close()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function modalHandler() {
        return {
            showModal: false,
            form: {
                id: null,
                tanggal: '',
                jenis: '',
                jumlah: ''
            },
            async edit(id) {
                const res = await fetch(`/tabungan/${id}/edit`)
                const data = await res.json()
                this.form = {
                    id: data.id,
                    tanggal: data.tanggal,
                    jenis: data.jenis,
                    jumlah: data.jumlah
                }
                this.showModal = true
            },
            close() {
                this.showModal = false
                this.form = {
                    id: null,
                    tanggal: '',
                    jenis: '',
                    jumlah: ''
                }
            }
        }
    }
</script>
@endsection
