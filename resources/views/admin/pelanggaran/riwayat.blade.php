@extends('sideadmin')

@section('konten')
<div class="container mx-auto p-6">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">
            📜 Riwayat Pelanggaran: {{ $santri->nama }} ({{ $santri->nis }})
        </h2>

        <a href="{{ route('pelanggaran.index') }}"
            class="inline-block mb-4 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded">⬅️ Kembali</a>

        @if ($riwayat->isEmpty())
        <p class="text-gray-600 dark:text-gray-300">Tidak ada data pelanggaran.</p>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white">
                    <tr>
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white">
                            <tr>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Kategori</th>
                                <th class="px-4 py-2 text-left">Keterangan</th>
                                <th class="px-4 py-2 text-left">Tindakan</th>
                                <th class="px-4 py-2 text-left">Pengurus</th>
                                <th class="px-4 py-2 text-left">Aksi</th>
                            </tr>
                        </thead>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($riwayat as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $item->created_at->format('d-m-Y H:i') }}</td>
                        <td class="px-4 py-2 capitalize">{{ $item->kategori }}</td>
                        <td class="px-4 py-2">{{ $item->keterangan }}</td>
                        <td class="px-4 py-2">{{ $item->tindakan }}</td>
                        <td class="px-4 py-2">{{ $item->pengurus->name ?? '-' }}</td>
                        <td class="px-4 py-2 space-x-1">
                            <a href="{{ route('pelanggaran.edit', $item->id) }}"
                                class="bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded transition text-xs">✏️ Edit</a>

                            <form action="{{ route('pelanggaran.hapus', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pelanggaran ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded transition text-xs">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection