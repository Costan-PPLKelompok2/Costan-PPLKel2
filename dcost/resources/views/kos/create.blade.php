<x-app-layout>
    <x-slot name="header">
        {{-- Judul Halaman yang Lebih Menonjol --}}
        <h1 class="h1 font-weight-bold text-center" style="color: #4c51bf; font-family: 'Montserrat', sans-serif; letter-spacing: 1px; text-shadow: 0px 2px 4px rgba(0,0,0,0.1);">
            {{ __('Tambah Kos Baru') }}
        </h1>
    </x-slot>

    <div class="container mt-5 mb-5"> {{-- Menambah margin-bottom --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; background-color: #e6f4e5; color: #155724; border: 1px solid #b0f2b0; font-family: 'Montserrat', sans-serif;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-lg border-0" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-white py-4" style="border-top-left-radius: 15px; border-top-right-radius: 15px; border-bottom: 1px solid #eee;">
                <h5 class="mb-0 fw-bold text-primary" style="font-family: 'Montserrat', sans-serif; font-size: 1.3rem;">Formulir Penambahan Kos</h5>
            </div>
            <div class="card-body p-5"> {{-- Menambah padding --}}
                <form method="POST" action="{{ route('kos.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4"> {{-- Menambah margin-bottom --}}
                        <label for="nama_kos" class="form-label fw-bold text-secondary" style="font-family: 'Montserrat', sans-serif;">Nama Kos</label>
                        <input type="text" class="form-control form-control-lg rounded-pill @error('nama_kos') is-invalid @enderror" id="nama_kos" name="nama_kos" placeholder="Contoh: Kos Mutiara Indah" value="{{ old('nama_kos') }}" required style="font-family: 'Montserrat', sans-serif;">
                        @error('nama_kos')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="deskripsi" class="form-label fw-bold text-secondary" style="font-family: 'Montserrat', sans-serif;">Deskripsi</label>
                        <textarea class="form-control rounded-lg @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" placeholder="Deskripsikan kos secara detail, seperti fasilitas umum, lingkungan, dll." required style="font-family: 'Montserrat', sans-serif;">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="alamat" class="form-label fw-bold text-secondary" style="font-family: 'Montserrat', sans-serif;">Alamat Lengkap</label>
                        <input type="text" class="form-control form-control-lg rounded-pill @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Contoh: Jl. Kebon Jeruk No. 10, Jakarta Barat" value="{{ old('alamat') }}" required style="font-family: 'Montserrat', sans-serif;">
                        @error('alamat')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="harga" class="form-label fw-bold text-secondary" style="font-family: 'Montserrat', sans-serif;">Harga Sewa</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-pill-start" style="font-family: 'Montserrat', sans-serif;">Rp</span>
                            <input type="number" class="form-control form-control-lg rounded-pill-end @error('harga') is-invalid @enderror" id="harga" name="harga" placeholder="Contoh: 1500000" min="0" value="{{ old('harga') }}" required style="font-family: 'Montserrat', sans-serif;">
                            @error('harga')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="fasilitas" class="form-label fw-bold text-secondary" style="font-family: 'Montserrat', sans-serif;">Fasilitas (pisahkan dengan koma)</label>
                        <textarea class="form-control rounded-lg @error('fasilitas') is-invalid @enderror" id="fasilitas" name="fasilitas" rows="3" placeholder="Contoh: AC, Kamar Mandi Dalam, Wi-Fi, Lemari" required style="font-family: 'Montserrat', sans-serif;">{{ old('fasilitas') }}</textarea>
                        @error('fasilitas')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- === Tambahan untuk Jenis Kos === --}}
                    <div class="mb-4">
                        <label for="jenis_kos" class="form-label fw-bold text-secondary" style="font-family: 'Montserrat', sans-serif;">Jenis Kos</label>
                        <select class="form-select form-select-lg rounded-pill @error('jenis_kos') is-invalid @enderror" id="jenis_kos" name="jenis_kos" required style="font-family: 'Montserrat', sans-serif;">
                            <option value="">Pilih Jenis Kos</option>
                            <option value="putra" {{ old('jenis_kos') == 'putra' ? 'selected' : '' }}>Putra</option>
                            <option value="putri" {{ old('jenis_kos') == 'putri' ? 'selected' : '' }}>Putri</option>
                            <option value="campur" {{ old('jenis_kos') == 'campur' ? 'selected' : '' }}>Campur</option>
                        </select>
                        @error('jenis_kos')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- === Tambahan untuk Durasi Sewa === --}}
                    <div class="mb-4">
                        <label for="durasi_sewa" class="form-label fw-bold text-secondary" style="font-family: 'Montserrat', sans-serif;">Durasi Sewa Tersedia</label>
                        <select class="form-select form-select-lg rounded-pill @error('durasi_sewa') is-invalid @enderror" id="durasi_sewa" name="durasi_sewa" required style="font-family: 'Montserrat', sans-serif;">
                            <option value="">Pilih Durasi Sewa</option>
                            <option value="bulanan" {{ old('durasi_sewa') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="tahunan" {{ old('durasi_sewa') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                        </select>
                        @error('durasi_sewa')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="foto" class="form-label fw-bold text-secondary" style="font-family: 'Montserrat', sans-serif;">Foto Kos (Opsional)</label>
                        <input class="form-control form-control-lg rounded-pill @error('foto') is-invalid @enderror" type="file" id="foto" name="foto" accept="image/*" style="font-family: 'Montserrat', sans-serif;">
                        <small class="form-text text-muted ms-2" style="font-family: 'Montserrat', sans-serif;">Unggah foto kos dalam format JPG, PNG, atau JPEG (maks. 2MB).</small>
                        @error('foto')
                            <div class="invalid-feedback d-block">{{ $message }}</div> {{-- d-block agar selalu terlihat --}}
                        @enderror
                    </div>

                    <div class="d-grid gap-2 mt-4"> {{-- Menggunakan d-grid untuk tombol full-width --}}
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm" style="font-family: 'Montserrat', sans-serif; font-weight: bold; padding: 12px 0;">
                            <i class="fas fa-save me-2"></i> Simpan Kos
                        </button>
                        <a href="{{ route('kos.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill" style="font-family: 'Montserrat', sans-serif; font-weight: bold; padding: 12px 0;">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- External Scripts & Styles --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa; /* Light background for the page */
        }
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-3px); /* Sedikit efek hover untuk kartu utama */
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        }
        .form-control.rounded-pill,
        .form-select.rounded-pill { /* Tambahkan .form-select di sini */
            border-radius: 50rem !important; /* Untuk input field dan select */
        }
        .form-control.rounded-lg {
            border-radius: 0.5rem !important; /* Untuk textarea */
        }
        .input-group-text.rounded-pill-start {
            border-top-left-radius: 50rem !important;
            border-bottom-left-radius: 50rem !important;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
        .form-control.rounded-pill-end {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-top-right-radius: 50rem !important;
            border-bottom-right-radius: 50rem !important;
        }
        .btn.rounded-pill {
            border-radius: 50rem !important;
        }
        .invalid-feedback {
            font-size: 0.875em; /* Ukuran font standar untuk pesan error */
            color: #dc3545; /* Warna merah untuk pesan error */
            margin-top: 0.25rem;
        }
    </style>
</x-app-layout>