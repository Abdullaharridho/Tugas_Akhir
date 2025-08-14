@extends('sideadmin')

@section('konten')

<div class="p-6 text-gray-800 dark:text-white"
    x-data="userAdminModal()"
    x-init="
        @if ($errors->any() && old('_form') === 'create')
            showCreate = true;
        @elseif ($errors->any() && old('_form') === 'edit')
            showEdit = true;
            editData = {
                id: '{{ old('id') }}',
                name: '{{ old('name') }}',
                email: '{{ old('email') }}',
            };
            updateUrl = '{{ url("user-admin") }}/{{ old('id') }}';
        @endif
     ">
   
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Daftar Pengurus</h2>
        <button @click="showCreate = true"
            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded shadow">
            <i class="fas fa-user-plus"></i> Tambah Pengurus
        </button>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded shadow">
        <table class="w-full border border-gray-300 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700 text-left">
                <tr>
                    <th class="px-4 py-2 border dark:border-gray-700">Nama</th>
                    <th class="px-4 py-2 border dark:border-gray-700">Email</th>
                    <th class="px-4 py-2 border dark:border-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admins as $admin)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                    <td class="px-4 py-2 border dark:border-gray-700">{{ $admin->name }}</td>
                    <td class="px-4 py-2 border dark:border-gray-700">{{ $admin->email }}</td>
                    <td class="px-4 py-2 border dark:border-gray-700 space-x-2">
                        <button @click="openEditModal({{ $admin->id }}, '{{ $admin->name }}', '{{ $admin->email }}')"
                            class="text-blue-600 hover:underline flex items-center gap-1">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form action="{{ route('useradmin.destroy', $admin->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Yakin hapus admin ini?')"
                                class="text-red-600 hover:underline flex items-center gap-1">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                        <form action="{{ route('useradmin.resetpassword', $admin->id) }}" method="POST" class="inline">
                            @csrf
                            <button onclick="return confirm('Reset password pengurus ini ?')"
                                class="text-purple-600 hover:underline flex items-center gap-1">
                                <i class="fas fa-key"></i> Reset Password
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah -->
    <div x-show="showCreate" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white dark:bg-gray-900 text-gray-800 dark:text-white p-6 rounded-lg w-full max-w-lg shadow-lg">
            <h3 class="text-xl font-semibold mb-4">Tambah Pengurus</h3>
            <form action="{{ route('useradmin.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_form" value="create">

                <div class="mb-4">
                    <label class="block font-semibold">Nama</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800"
                        value="{{ old('_form') === 'create' ? old('name') : '' }}" required>
                    @if ($errors->has('name') && old('_form') === 'create')
                    <span class="text-red-500 text-sm">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Email</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800"
                        value="{{ old('_form') === 'create' ? old('email') : '' }}" required>
                    @if ($errors->has('email') && old('_form') === 'create')
                    <span class="text-red-500 text-sm">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Password</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800" required>
                    @if ($errors->has('password') && old('_form') === 'create')
                    <span class="text-red-500 text-sm">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showCreate = false" class="bg-gray-300 dark:bg-gray-700 px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="showEdit" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white dark:bg-gray-900 text-gray-800 dark:text-white p-6 rounded-lg w-full max-w-lg shadow-lg">
            <h3 class="text-xl font-semibold mb-4">Edit Pengurus</h3>
            <form :action="updateUrl" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_form" value="edit">
                <input type="hidden" name="id" :value="editData.id">

                <div class="mb-4">
                    <label class="block font-semibold">Nama</label>
                    <input type="text" name="name" x-model="editData.name"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800" required>
                    @if ($errors->has('name') && old('_form') === 'edit')
                    <span class="text-red-500 text-sm">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Email</label>
                    <input type="email" name="email" x-model="editData.email"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800" required>
                    @if ($errors->has('email') && old('_form') === 'edit')
                    <span class="text-red-500 text-sm">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="showEdit = false" class="bg-gray-300 dark:bg-gray-700 px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function userAdminModal() {
        return {
            showCreate: false,
            showEdit: false,
            updateUrl: '',
            editData: {
                id: null,
                name: '',
                email: '',
            },
            openEditModal(id, name, email) {
                this.editData = {
                    id,
                    name,
                    email
                };
                this.updateUrl = '{{ url("user-admin") }}/' + id;
                this.showEdit = true;
            }
        };
    }
</script>
@endsection