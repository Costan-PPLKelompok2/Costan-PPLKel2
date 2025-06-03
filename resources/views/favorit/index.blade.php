<x-app-layout>
    <x-slot name="header">
        <h2 class="h2 font-weight-bold text-center text-primary" style="font-family: 'Montserrat', sans-serif;">
            {{ __('Kos Favorit Saya') }}
        </h2>
    </x-slot>

    <div class="container mt-5">
        {{-- Main Card for Search, Filters, and Kos Listings --}}
        <div class="card shadow-lg mb-4 main-card">
            <div class="card-header bg-white py-4" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <form id="filterForm" action="{{ route('kos.favorites') }}" method="GET">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-5 col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg search-input" name="search" placeholder="Cari nama kos" value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary bg-primary text-white search-button" type="submit">
                                    <i class="fas fa-search"></i> <span class="ms-2 d-none d-md-inline">Cari</span>
                                </button>
                            </div>
                        </div>

                        {{-- Dropdown for Jenis Kos --}}
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="dropdown w-100">
                                <button class="btn btn-outline-secondary dropdown-toggle filter-button w-100" type="button" id="jenisKosDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ request('jenis_kos') ? (request('jenis_kos') == 'Putra' ? 'Putra' : (request('jenis_kos') == 'Putri' ? 'Putri' : (request('jenis_kos') == 'Campur' ? 'Campur' : 'Umum'))) : 'Semua Jenis Kos' }}
                                </button>
                                <ul class="dropdown-menu filter-dropdown-menu" aria-labelledby="jenisKosDropdown">
                                    <li><a class="dropdown-item" href="{{ route('kos.favorites', array_merge(request()->except(['jenis_kos', 'page']), ['jenis_kos' => ''])) }}">Semua Jenis Kos</a></li>
                                    <li><a class="dropdown-item" href="{{ route('kos.favorites', array_merge(request()->except(['jenis_kos', 'page']), ['jenis_kos' => 'Putra'])) }}">Putra</a></li>
                                    <li><a class="dropdown-item" href="{{ route('kos.favorites', array_merge(request()->except(['jenis_kos', 'page']), ['jenis_kos' => 'Putri'])) }}">Putri</a></li>
                                    <li><a class="dropdown-item" href="{{ route('kos.favorites', array_merge(request()->except(['jenis_kos', 'page']), ['jenis_kos' => 'Campur'])) }}">Campur</a></li>
                                    <li><a class="dropdown-item" href="{{ route('kos.favorites', array_merge(request()->except(['jenis_kos', 'page']), ['jenis_kos' => 'Umum'])) }}">Umum</a></li>
                                </ul>
                            </div>
                        </div>

                        {{-- Dropdown for Durasi Sewa --}}
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="dropdown w-100">
                                <button class="btn btn-outline-secondary dropdown-toggle filter-button w-100" type="button" id="durasiSewaDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ request('durasi_sewa') ? (request('durasi_sewa') == 'Bulanan' ? 'Bulanan' : (request('durasi_sewa') == 'Tahunan' ? 'Tahunan' : 'Mingguan')) : 'Semua Durasi' }}
                                </button>
                                <ul class="dropdown-menu filter-dropdown-menu" aria-labelledby="durasiSewaDropdown">
                                    <li><a class="dropdown-item" href="{{ route('kos.favorites', array_merge(request()->except(['durasi_sewa', 'page']), ['durasi_sewa' => ''])) }}">Semua Durasi</a></li>
                                    <li><a class="dropdown-item" href="{{ route('kos.favorites', array_merge(request()->except(['durasi_sewa', 'page']), ['durasi_sewa' => 'Bulanan'])) }}">Bulanan</a></li>
                                    <li><a class="dropdown-item" href="{{ route('kos.favorites', array_merge(request()->except(['durasi_sewa', 'page']), ['durasi_sewa' => 'Tahunan'])) }}">Tahunan</a></li>
                                    <li><a class="dropdown-item" href="{{ route('kos.favorites', array_merge(request()->except(['durasi_sewa', 'page']), ['durasi_sewa' => 'Mingguan'])) }}">Mingguan</a></li>
                                </ul>
                            </div>
                        </div>

                        {{-- Dropdown for Sort --}}
                        <div class="col-lg-3 col-md-4 col-sm-12 text-lg-end text-md-start text-sm-start">
                            <div class="dropdown d-inline-block w-100 w-md-auto">
                                <button class="btn btn-outline-secondary dropdown-toggle sort-button w-100" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-sort"></i> {{ request('sort') == 'terlama' ? 'Terlama' : 'Terbaru' }}
                                </button>
                                <ul class="dropdown-menu sort-dropdown-menu" aria-labelledby="sortDropdown">
                                    <li><a class="dropdown-item" href="{{ route('kos.favorites', array_merge(request()->except(['sort', 'page']), ['sort' => 'terbaru'])) }}">Terbaru</a></li>
                                    <li><a class="dropdown-item" href="{{ route('kos.favorites', array_merge(request()->except(['sort', 'page']), ['sort' => 'terlama'])) }}">Terlama</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show custom-alert success-alert" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @elseif(session('info'))
                    <div class="alert alert-info alert-dismissible fade show custom-alert info-alert" role="alert">
                        <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($kosFavorit->isEmpty())
                    <div class="alert alert-warning text-center shadow-sm empty-favorites-alert" role="alert">
                        <i class="fas fa-heart-broken fa-2x mb-3"></i>
                        <p class="h5 fw-bold">Belum ada kos yang difavoritkan.</p>
                        <p class="text-muted">Tambahkan kos favorit Anda untuk melihatnya di sini.</p>
                    </div>
                @else
                    <div class="row g-4 justify-content-center">
                        @foreach($kosFavorit as $kos)
                            <div class="col-md-6 col-lg-4 d-flex">
                                <div class="card shadow-sm border-0 flex-fill kos-card">
                                    <div class="position-relative">
                                        @if($kos->foto)
                                            <img src="{{ asset('storage/' . $kos->foto) }}" class="card-img-top object-fit-cover kos-image" alt="Foto Kos">
                                        @else
                                            <img src="https://placehold.co/400x200/e0e0e0/555555?text=Tidak+ada+foto" class="card-img-top object-fit-cover kos-image" alt="Tidak ada foto">
                                        @endif
                                        {{-- Badge Jenis Kos --}}
                                        @if($kos->jenis_kos)
                                            <span class="badge bg-primary position-absolute top-0 start-0 m-3 p-2 rounded-pill kos-type-badge">{{ $kos->jenis_kos }}</span>
                                        @endif
                                    </div>
                                    <div class="card-body p-4 d-flex flex-column">
                                        <h5 class="card-title fw-bold text-dark mb-1 kos-name text-truncate" title="{{ $kos->nama_kos }}">{{ $kos->nama_kos }}</h5>
                                        <p class="card-subtitle text-muted mb-3 kos-address text-truncate" title="{{ $kos->alamat }}"><i class="fas fa-map-marker-alt me-1"></i> {{ $kos->alamat }}</p>

                                        @if($kos->durasi_sewa)
                                            <p class="card-text mb-1"><small class="text-muted">Durasi Sewa: <span class="fw-semibold">{{ $kos->durasi_sewa }}</span></small></p>
                                        @endif

                                        <hr class="my-3">

                                        <ul class="list-unstyled mb-0 kos-facilities flex-grow-1">
                                            <li>
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Fasilitas: <span class="facility-text" title="{{ $kos->fasilitas }}">{{ \Illuminate\Support\Str::limit($kos->fasilitas, 80, '...') }}</span>
                                            </li>
                                        </ul>

                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                            <h4 class="text-primary fw-bold mb-0 kos-price">
                                                Rp {{ number_format($kos->harga, 0, ',', '.') }}<small class="text-muted ms-1 price-suffix">/{{ strtolower($kos->durasi_sewa ?? 'bulan') }}</small>
                                            </h4>
                                            <form action="{{ route('kos.unfavorite', $kos->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kos ini dari favorit?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill remove-favorite-button">
                                                    <i class="fas fa-heart-broken me-1"></i> Hapus
                                                </button>
                                            </form>
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

    {{-- Bootstrap & FontAwesome --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{-- Google Fonts for Montserrat --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }
        .main-card {
            border-radius: 15px;
        }
        .search-input,
        .search-button,
        .sort-button,
        .filter-button { /* Changed from .filter-select to .filter-button */
            font-family: 'Montserrat', sans-serif;
        }
        .search-input {
            border-radius: 10px 0 0 10px;
        }
        .search-button {
            border-radius: 0 10px 10px 0;
        }
        /* New style for the filter buttons */
        .filter-button {
            border-radius: 10px;
            text-align: left; /* Align text to the left */
            position: relative; /* Needed for absolute positioning of the caret */
            padding-right: 2.5rem; /* Add padding for the caret icon */
            white-space: nowrap; /* Prevent text wrapping */
            overflow: hidden; /* Hide overflow content */
            text-overflow: ellipsis; /* Add ellipsis for overflow */
        }
        .filter-button::after {
            position: absolute;
            right: 1rem; /* Adjust caret position */
            top: 50%;
            transform: translateY(-50%);
            vertical-align: 0; /* Override Bootstrap's default */
        }
        .filter-dropdown-menu,
        .sort-dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .filter-dropdown-menu .dropdown-item,
        .sort-dropdown-menu .dropdown-item {
            font-family: 'Montserrat', sans-serif;
            padding: 0.75rem 1.25rem; /* Adjust padding for dropdown items */
        }
        .filter-dropdown-menu .dropdown-item:hover,
        .sort-dropdown-menu .dropdown-item:hover {
            background-color: #f0f0f0;
            color: #0d6efd; /* Bootstrap primary color */
        }


        /* Custom Alerts */
        .custom-alert {
            border-radius: 10px;
            font-family: 'Montserrat', sans-serif;
        }
        .success-alert {
            background-color: #e6f4e5;
            color: #155724;
            border: 1px solid #b0f2b0;
        }
        .info-alert {
            background-color: #e0f7fa;
            color: #0c5460;
            border: 1px solid #b3e5fc;
        }
        .empty-favorites-alert {
            background-color: #fff3cd;
            color: #664d03;
            border: 1px solid #ffecb5;
            padding: 2rem;
        }
        .empty-favorites-alert i {
            color: #664d03;
        }

        /* Kos Card Styling */
        .kos-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            min-height: 440px; /* Adjusted min-height slightly */
            border-radius: 15px;
            overflow: hidden;
            background-color: #fff;
            display: flex;
            flex-direction: column;
        }
        .kos-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        .kos-image {
            height: 200px;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            width: 100%;
            object-fit: cover;
        }
        .kos-name {
            font-size: 1.4rem;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.2;
        }
        .kos-address {
            font-size: 0.95rem;
            font-family: 'Montserrat', sans-serif;
        }
        .kos-facilities {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.95rem;
            margin-bottom: 1rem; /* Added margin-bottom for spacing */
        }
        /* Specific styling for the facility text to control overflow */
        .kos-facilities .facility-text {
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Limit to 2 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal; /* Allow text to wrap */
            word-wrap: break-word; /* Break long words */
        }
        .kos-price {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
        }
        .price-suffix {
            font-size: 0.7em;
        }
        .remove-favorite-button {
            font-family: 'Montserrat', sans-serif;
        }
        hr {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }
        /* Badge Jenis Kos */
        .kos-type-badge {
            font-size: 0.85em;
            background-color: rgba(0, 123, 255, 0.9) !important;
            color: white;
        }

        /* Responsive adjustments for filter/sort section */
        @media (max-width: 991.98px) { /* Medium devices (tablets, 768px and up) */
            .card-header .row > div {
                margin-bottom: 0.5rem;
            }
            .card-header .row > div:last-child {
                margin-bottom: 0;
            }
            .text-lg-end {
                text-align: start !important;
            }
        }
        @media (max-width: 767.98px) { /* Small devices (landscape phones, 576px and up) */
            .kos-image {
                height: 180px;
            }
            .search-input {
                border-radius: 10px;
            }
            .search-button {
                border-radius: 10px;
                margin-top: 0.5rem;
                width: 100%;
            }
            .input-group {
                flex-wrap: wrap;
            }
            .filter-button, /* Changed from .filter-select to .filter-button */
            .sort-button {
                width: 100%;
            }
            .card-header .row > div {
                margin-bottom: 0.75rem;
            }
        }
    </style>
</x-app-layout>