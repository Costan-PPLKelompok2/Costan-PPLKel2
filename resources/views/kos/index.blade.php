<x-app-layout>
    <x-slot name="header">
        <h1 class="h1 font-weight-bold text-center" style="color: #4c51bf; font-family: 'Montserrat', sans-serif; letter-spacing: 1px; text-shadow: 0px 2px 4px rgba(0,0,0,0.1);">
            {{ __('Daftar Kos Saya') }}
        </h1>
    </x-slot>

    <div class="container mt-5">
        {{-- Main Card for Statistics, Search, Filters, and Kos Listings --}}
        <div class="card shadow-lg mb-4 main-card" style="border-radius: 15px;">
            <div class="card-header bg-white py-4" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                {{-- Total Kos & Total Penghuni Cards (Minimalis dengan Border) --}}
                <div class="row mb-4 justify-content-center g-3">
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100 stat-card total-kos-card" style="border-radius: 10px;">
                            <div class="card-body text-center p-3">
                                <h6 class="card-title text-uppercase text-muted mb-1" style="font-size: 0.9rem; font-family: 'Montserrat', sans-serif;">Total Kos</h6>
                                <p class="h4 fw-bold mb-0" style="font-size: 1.8rem; font-family: 'Montserrat', sans-serif;">{{ $totalKos }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100 stat-card total-penghuni-card" style="border-radius: 10px;">
                            <div class="card-body text-center p-3">
                                <h6 class="card-title text-uppercase text-muted mb-1" style="font-size: 0.9rem; font-family: 'Montserrat', sans-serif;">Total Penghuni</h6>
                                <p class="h4 fw-bold mb-0" style="font-size: 1.8rem; font-family: 'Montserrat', sans-serif;">{{ $totalPenghuni }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Search and Filter Section --}}
                <form action="{{ route('kos.index') }}" method="GET" class="row g-3 align-items-center flex-wrap">
                    {{-- Kolom untuk Search --}}
                    <div class="col-12 col-lg-4 col-md-5">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg search-input" name="search" placeholder="Cari nama kos" value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary bg-primary text-white search-button" type="submit">
                                <i class="fas fa-search"></i> <span class="ms-2 d-none d-md-inline">Cari</span>
                            </button>
                        </div>
                    </div>

                    {{-- Dropdown untuk Jenis Kos --}}
                    <div class="col-6 col-lg-2 col-md-2">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-secondary dropdown-toggle filter-button w-100" type="button" id="jenisKosDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ request('jenis_kos')
                                        ? (request('jenis_kos') == 'putra' ? 'Putra'
                                        : (request('jenis_kos') == 'putri' ? 'Putri' : 'Campur'))
                                        : 'Semua Jenis Kos'
                                }}
                            </button>
                            <ul class="dropdown-menu filter-dropdown-menu" aria-labelledby="jenisKosDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('kos.index', array_merge(request()->except(['jenis_kos','page']), ['jenis_kos' => ''])) }}">
                                        Semua Jenis Kos
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('kos.index', array_merge(request()->except(['jenis_kos','page']), ['jenis_kos' => 'putra'])) }}">
                                        Putra
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('kos.index', array_merge(request()->except(['jenis_kos','page']), ['jenis_kos' => 'putri'])) }}">
                                        Putri
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('kos.index', array_merge(request()->except(['jenis_kos','page']), ['jenis_kos' => 'campur'])) }}">
                                        Campur
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Dropdown untuk Durasi Sewa --}}
                    <div class="col-6 col-lg-2 col-md-2">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-secondary dropdown-toggle filter-button w-100" type="button" id="durasiSewaDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ request('durasi_sewa')
                                        ? (request('durasi_sewa') == 'bulanan' ? 'Bulanan' : 'Tahunan')
                                        : 'Semua Durasi'
                                }}
                            </button>
                            <ul class="dropdown-menu filter-dropdown-menu" aria-labelledby="durasiSewaDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('kos.index', array_merge(request()->except(['durasi_sewa','page']), ['durasi_sewa' => ''])) }}">
                                        Semua Durasi
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('kos.index', array_merge(request()->except(['durasi_sewa','page']), ['durasi_sewa' => 'bulanan'])) }}">
                                        Bulanan
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('kos.index', array_merge(request()->except(['durasi_sewa','page']), ['durasi_sewa' => 'tahunan'])) }}">
                                        Tahunan
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Dropdown untuk Urutkan --}}
                    <div class="col-6 col-lg-2 col-md-1">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-secondary dropdown-toggle sort-button w-100" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-sort"></i> {{ request('sort') == 'terlama' ? 'Terlama' : 'Terbaru' }}
                            </button>
                            <ul class="dropdown-menu sort-dropdown-menu" aria-labelledby="sortDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('kos.index', array_merge(request()->query(), ['sort' => 'terbaru'])) }}">
                                        Terbaru
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('kos.index', array_merge(request()->query(), ['sort' => 'terlama'])) }}">
                                        Terlama
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Tombol Tambah Kos --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('kos.create') }}" class="btn btn-success text-white add-kos-button">
                            <i class="fas fa-plus me-2"></i> Tambah Kos
                        </a>
                        <a href="{{ route('admin.index') }}" class="btn btn-primary text-white add-kos-button">
                            <i class="fas fa-statistic me-2"></i> Lihat Statistik
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-body p-4">
                {{-- Alerts --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; background-color: #e6f4e5; color: #155724; border: 1px solid #b0f2b0; font-family: 'Montserrat', sans-serif;">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 10px;">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 10px;">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                {{-- Kos List Display --}}
                @if($kosList->isEmpty())
                    <div class="alert alert-info text-center shadow-sm" style="border-radius: 15px; background-color: #e0f7fa; color: #0c4160; border: 1px solid #b0e0e6; padding: 2rem; font-family: 'Montserrat', sans-serif;">
                        <i class="fas fa-info-circle fa-2x mb-3" style="color: #0c4160;"></i>
                        <p class="h5 fw-bold">Belum ada kos yang ditambahkan.</p>
                        <p class="text-muted">Mulai tambahkan kos Anda sekarang!</p>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach($kosList as $kos)
                            <div class="col-12 d-flex">
                                <div class="kos-item-wrapper shadow-lg flex-fill" style="border-radius: 15px; overflow: hidden; background: white;">
                                    <div class="row g-0 h-100">
                                        <div class="col-md-5">
                                            @if($kos->foto)
                                                <img src="{{ asset('storage/' . $kos->foto) }}" class="img-fluid kos-item-image" alt="Foto Kos" style="border-top-left-radius: 15px; border-bottom-left-radius: 15px;">
                                            @else
                                                <img src="https://placehold.co/400x300/e0e0e0/555555?text=Tidak+ada+foto" class="img-fluid kos-item-image" alt="Tidak ada foto" style="border-top-left-radius: 15px; border-bottom-left-radius: 15px;">
                                            @endif
                                        </div>
                                        <div class="col-md-7 d-flex flex-column">
                                            <div class="kos-item-body d-flex flex-column justify-content-between p-4 flex-grow-1">
                                                <div>
                                                    <h5 class="card-title fw-bold text-primary mb-2" style="font-size: 1.5rem; font-family: 'Montserrat', sans-serif;">{{ $kos->nama_kos }}</h5>
                                                    <p class="card-text text-muted mb-3" style="font-family: 'Montserrat', sans-serif;">{{ \Illuminate\Support\Str::limit($kos->deskripsi, 150) }}</p>
                                                    <p class="mb-1 text-dark" style="font-family: 'Montserrat', sans-serif;">
                                                        <strong>Harga:</strong>
                                                        <span class="fw-bold text-success">Rp {{ number_format($kos->harga, 0, ',', '.') }}</span>
                                                    </p>
                                                    <p class="mb-1 text-dark" style="font-family: 'Montserrat', sans-serif;">
                                                        <strong>Jenis Kos:</strong> {{ ucfirst($kos->jenis_kos) }}
                                                    </p>
                                                    <p class="mb-1 text-dark" style="font-family: 'Montserrat', sans-serif;">
                                                        <strong>Durasi Sewa:</strong> {{ ucfirst($kos->durasi_sewa) }}
                                                    </p>
                                                    <p class="text-dark" style="font-family: 'Montserrat', sans-serif;">
                                                        <strong>Alamat:</strong> {{ $kos->alamat }}
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center mb-0">
                                                        <p class="text-dark mb-0" style="font-family: 'Montserrat', sans-serif;">
                                                            <strong>Fasilitas:</strong>
                                                            <span class="facility-text">{{ \Illuminate\Support\Str::limit($kos->fasilitas, 80) }}</span>
                                                        </p>
                                                        <div class="d-flex align-items-center gap-3">
                                                            {{-- Tombol Edit & Hapus (hanya pemilik kos) --}}
                                                            @if(auth()->id() === $kos->user_id)
                                                                <a href="{{ route('kos.edit', $kos->id) }}" class="btn btn-link text-warning p-0" style="font-family: 'Montserrat', sans-serif; text-decoration: none;">
                                                                    <i class="fas fa-edit me-1"></i> Edit
                                                                </a>
                                                                <form action="{{ route('kos.destroy', $kos->id) }}" method="POST" style="display: inline-block;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Yakin ingin menghapus kos ini?')" style="font-family: 'Montserrat', sans-serif; text-decoration: none;">
                                                                        <i class="fas fa-trash me-1"></i> Hapus
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            {{-- Tombol Review (sekarang mengarah ke review.show) --}}
                                                            <a href="{{ route('review.show', $kos->id) }}" class="btn btn-link text-info p-0" style="font-family: 'Montserrat', sans-serif; text-decoration: none;">
                                                                <i class="fas fa-comments me-1"></i> Review
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
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
            background-color: #f8f9fa;
        }
        .main-card {
            border-radius: 15px;
        }
        .kos-item-wrapper {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            margin-bottom: 20px;
            min-height: 280px;
            display: flex;
            flex-direction: column;
        }
        .kos-item-wrapper:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .kos-item-image {
            height: 250px;
            width: 100%;
            object-fit: cover;
        }
        .facility-text {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
            word-wrap: break-word;
        }

        /* --- Perbaikan Kotak Pencarian & Penambahan Highlight Statistik --- */
        .search-input {
            border-radius: 10px 0 0 10px !important;
            font-family: 'Montserrat', sans-serif !important;
            padding: 0.6rem 1rem !important;
            height: 42px !important;
            font-size: 1rem !important;
            border: 1px solid #ced4da !important;
        }
        .search-input:focus {
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, .25) !important;
        }
        .search-button {
            border-radius: 0 10px 10px 0 !important;
            font-family: 'Montserrat', sans-serif !important;
            padding: 0.6rem 1rem !important;
            height: 42px !important;
            font-size: 1rem !important;
            display: flex;
            align-items: center;
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #fff !important;
        }
        .input-group {
            flex-wrap: nowrap;
        }
        @media (max-width: 767.98px) {
            .input-group {
                flex-wrap: wrap;
            }
            .search-input {
                border-radius: 10px !important;
                margin-bottom: 0.5rem;
            }
            .search-button {
                border-radius: 10px !important;
                margin-top: 0.5rem;
                border-left: 1px solid #0d6efd !important;
            }
        }

        .sort-button,
        .filter-button {
            border-radius: 10px !important;
            font-family: 'Montserrat', sans-serif !important;
            text-align: left !important;
            position: relative !important;
            padding: 0.6rem 1rem !important;
            height: 42px !important;
            font-size: 1rem !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #ced4da !important;
            color: #495057 !important;
            background-color: #fff !important;
        }
        .sort-button:hover,
        .filter-button:hover {
            border-color: #80bdff !important;
            background-color: #e9ecef !important;
        }
        .sort-button:focus,
        .filter-button:focus {
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, .25) !important;
            outline: 0 !important;
        }
        .sort-button::after,
        .filter-button::after {
            margin-left: 0.5rem;
            vertical-align: 0.255em;
            content: ""; /* Menambahkan properti content yang hilang */
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
        }
    </style>
</x-app-layout>