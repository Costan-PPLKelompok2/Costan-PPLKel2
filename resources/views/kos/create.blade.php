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

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Tambah Kos</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('kos.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                    <div class="mb-3">
                        <label for="nama_kos" class="form-label">Nama Kos</label>
                        <input type="text" class="form-control" id="nama_kos" name="nama_kos" placeholder="Masukkan nama kos" required>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi kos" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat kos" required>
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" id="harga" name="harga" placeholder="Masukkan harga kos" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="fasilitas" class="form-label">Fasilitas</label>
                        <textarea class="form-control" id="fasilitas" name="fasilitas" rows="2" placeholder="Masukkan fasilitas kos, pisahkan dengan koma" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Kos</label>
                        <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto" name="foto" accept="image/*">
                        <small class="form-text text-muted">Unggah foto kos dalam format JPG, PNG, atau JPEG.</small>
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>