@extends('profile_management.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow-md">
    <h1 class="text-2xl font-bold mb-4">Edit Profil</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1 font-semibold">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="block mb-1 font-semibold">No Telepon</label>
            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-semibold">Alamat</label>
            <textarea name="address" class="w-full border p-2 rounded">{{ old('address', $user->address) }}</textarea>
        </div>

        <div>
            <label class="block mb-1 font-semibold">Foto Profil</label>
            <input type="file" name="profile_photo" id="profile_photo" class="w-full border p-2 rounded" onchange="previewImage(event)">
            <div class="mt-4">
                <img 
                    id="photo-preview" 
                    class="w-24 h-24 rounded-full object-cover {{ $user->profile_photo_path ? '' : 'hidden' }}" 
                    src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : '' }}" 
                />
            </div>
        </div>

        <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('photo-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        </script>

        <div>
            <label class="block mb-1 font-semibold">Preferensi Lokasi</label>
            <input type="text" name="preferred_location" value="{{ old('preferred_location', $user->preferred_location) }}" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-semibold">Preferensi Tipe Kos</label>
            <input type="text" name="preferred_kos_type" value="{{ old('preferred_kos_type', $user->preferred_kos_type) }}" class="w-full border p-2 rounded">
        </div>

        <div class="flex gap-4">
            <div class="w-1/2">
                <label class="block mb-1 font-semibold">Harga Minimum</label>
                <input type="number" step="0.01" name="price_min" value="{{ old('price_min', $user->price_min) }}" class="w-full border p-2 rounded">
            </div>
            <div class="w-1/2">
                <label class="block mb-1 font-semibold">Harga Maksimum</label>
                <input type="number" step="0.01" name="price_max" value="{{ old('price_max', $user->price_max) }}" class="w-full border p-2 rounded">
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Simpan Perubahan</button>

            <!-- Tombol Hapus Akun -->
            <button type="button" onclick="openDeleteModal()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Hapus Akun</button>

            <form id="delete-account-form" method="POST" action="{{ route('profile.destroy') }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>

            <!-- Modal Konfirmasi -->
            <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
                <div class="bg-white p-6 rounded shadow-md">
                    <h2 class="text-xl font-bold mb-4">Konfirmasi Hapus Akun</h2>
                    <p class="mb-4">Apakah Anda yakin ingin menghapus akun Anda? Ini tidak bisa dibatalkan.</p>
                    <div class="flex gap-4">
                        <button onclick="submitDelete()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Ya, Hapus</button>
                        <button onclick="closeDeleteModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                    </div>
                </div>
            </div>

            <script>
                function openDeleteModal() {
                    document.getElementById('delete-modal').classList.remove('hidden');
                }

                function closeDeleteModal() {
                    document.getElementById('delete-modal').classList.add('hidden');
                }

                function submitDelete() {
                    document.getElementById('delete-account-form').submit();
                }
            </script>
        </div>
    </form>
</div>
@endsection
