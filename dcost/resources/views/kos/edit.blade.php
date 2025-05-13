<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit Kos') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Edit Kos</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('kos.update', $kos->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama_kos" class="form-label">Nama Kos</label>
                        <input type="text" class="form-control @error('nama_kos') is-invalid @enderror" id="nama_kos" name="nama_kos" value="{{ old('nama_kos', $kos->nama_kos) }}" placeholder="Masukkan nama kos" required>
                        @error('nama_kos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi kos" required>{{ old('deskripsi', $kos->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat', $kos->alamat) }}" placeholder="Masukkan alamat kos" required>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga', $kos->harga) }}" placeholder="Masukkan harga kos" min="0" required>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="fasilitas" class="form-label">Fasilitas</label>
                        <textarea class="form-control @error('fasilitas') is-invalid @enderror" id="fasilitas" name="fasilitas" rows="2" placeholder="Masukkan fasilitas kos, pisahkan dengan koma" required>{{ old('fasilitas', $kos->fasilitas) }}</textarea>
                        @error('fasilitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Kos</label>
                        <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto" name="foto" accept="image/*">
                        <small class="form-text text-muted">Unggah foto kos dalam format JPG, PNG, atau JPEG. Kosongkan jika tidak ingin mengganti foto.</small>
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($kos->foto)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $kos->foto) }}" alt="Foto Kos" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>