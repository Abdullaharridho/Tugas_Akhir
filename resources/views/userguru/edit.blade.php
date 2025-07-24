@extends('layout')

@section('content')
<h2 class="text-2xl font-bold text-center mb-6 text-gray-800 dark:text-white tracking-wide">
    ✏️ Edit Absensi - {{ $tanggal }}
</h2>

<div x-data x-init="$el.classList.add('opacity-0'); setTimeout(() => $el.classList.remove('opacity-0'), 100)"
    class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-2xl transition-opacity duration-700 opacity-100">

    <div class="mb-4 text-right text-sm text-gray-700 dark:text-gray-300">
        <strong>Dicatat oleh:</strong> {{ $absensi->first()->guru->name ?? 'Tidak Diketahui' }}
    </div>

    <form action="{{ route('absensi.update') }}" method="POST">
        @csrf

        {{-- Hidden Identitas Absensi --}}
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
        <input type="hidden" name="kelas" value="{{ $kelas }}">
        <input type="hidden" name="mapel" value="{{ $mapel }}">

        <table class="w-full text-sm border border-gray-300 dark:border-gray-600 rounded-md overflow-hidden shadow-md">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white">
                <tr>
                    <th class="px-4 py-2 border">NIS</th>
                    <th class="px-4 py-2 border">Nama Santri</th>
                    <th class="px-4 py-2 border">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensi as $a)
                <tr class="border hover:bg-blue-50 dark:hover:bg-gray-700 transition-all duration-200">
                    <td class="px-4 py-2 border text-gray-800 dark:text-white">
                        <input type="hidden" name="nis[]" value="{{ $a->nis }}">
                        {{ $a->nis }}
                    </td>
                    <td class="px-4 py-2 border text-gray-800 dark:text-white">
                        {{ $santriMap[$a->nis] ?? 'Nama Tidak Diketahui' }}
                    </td>
                    <td class="px-4 py-2 border">
                        <div class="flex flex-wrap gap-2 text-sm">
                            @foreach(['Hadir' => 'green', 'Alpha' => 'red', 'Izin' => 'yellow', 'Sakit' => 'blue'] as $status => $color)
                            <label class="text-{{ $color }}-600 dark:text-{{ $color }}-400">
                                <input type="radio" name="status[{{ $a->nis }}]" value="{{ $status }}" {{ $a->status === $status ? 'checked' : '' }}>
                                {{ $status }}
                            </label>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit"
            class="mt-6 w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition-all transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
        </button>
    </form>
</div>
@endsection
