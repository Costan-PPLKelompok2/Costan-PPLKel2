<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Tambah Kos') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('kos.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="nama_kos" class="form-label">Nama Kos</label>
                <input type="text" class="form-control" id="nama_kos" name="nama_kos" required>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" required>
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga (Rp)</label>
                <input type="number" class="form-control" id="harga" name="harga" required>
            </div>

            <div class="mb-3">
                <label for="fasilitas" class="form-label">Fasilitas</label>
                <textarea class="form-control" id="fasilitas" name="fasilitas" rows="2" required></textarea>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto Kos</label>
                <input class="form-control" type="file" id="foto" name="foto">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</x-app-layout>
