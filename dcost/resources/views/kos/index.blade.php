<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-center" style="color: #4c51bf;";>
            {{ __('Daftar Kos Saya') }}
        </h2>
    </x-slot>

    <div class="container mt-5">
        <div class="row mb-4 justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg h-100" style="border-radius: 15px;
                            background: linear-gradient(to bottom, #ffffff, #f0f0f0);">
                    <div class="card-body bg-info text-white rounded-top text-center" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                        <h5 class="card-title text-uppercase fw-bold" style="font-size: 1.3rem; font-family: 'Montserrat', sans-serif;">Total Kos</h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="h1 fw-bold text-primary" style="font-size: 2.5rem; font-family: 'Montserrat', sans-serif;">{{ $totalKos }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-lg h-100" style="border-radius: 15px;
                            background: linear-gradient(to bottom, #ffffff, #f0f0f0);">
                    <div class="card-body bg-warning text-white rounded-top text-center" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                        <h5 class="card-title text-uppercase fw-bold" style="font-size: 1.3rem; font-family: 'Montserrat', sans-serif;">Total Penghuni</h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="h1 fw-bold text-warning" style="font-size: 2.5rem; font-family: 'Montserrat', sans-serif;">{{ $totalPenghuni }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-lg" style="border-radius: 15px;">
            <div class="card-header bg-white py-4" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <form action="{{ route('kos.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg" name="search" placeholder="Cari nama kos atau alamat..." value="{{ request('search') }}" style="border-radius: 10px 0 0 10px; font-family: 'Montserrat', sans-serif;">
                                <button class="btn btn-outline-secondary bg-primary text-white" type="submit" style="border-radius: 0 10px 10px 0; font-family: 'Montserrat', sans-serif;">
                                    <i class="fas fa-search"></i> <span class="ms-2">Cari</span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="dropdown d-inline-block me-2">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-family: 'Montserrat', sans-serif;">
                                <i class="fas fa-sort"></i> Urutkan
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown" style="border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <li><a class="dropdown-item" href="{{ route('kos.index', ['sort' => 'terbaru', 'search' => request('search') ]) }}" style="font-family: 'Montserrat', sans-serif;">Terbaru</a></li>
                                <li><a class="dropdown-item" href="{{ route('kos.index', ['sort' => 'terlama', 'search' => request('search') ]) }}" style="font-family: 'Montserrat', sans-serif;">Terlama</a></li>
                            </ul>
                        </div>
                        <a href="{{ route('kos.create') }}" class="btn btn-success" style="border-radius: 10px; font-family: 'Montserrat', sans-serif;">
                            <i class="fas fa-plus me-2"></i> Tambah Kos
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; background-color: #e6f4e5; color: #155724; border: 1px solid #b0f2b0;">
                        <i class="fas fa-check-circle me-2"></i> <span style="font-family: 'Montserrat', sans-serif;">{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($kosList->isEmpty())
                    <div class="alert alert-info" style="border-radius: 10px; background-color: #e0f7fa; color: #0c4160; border: 1px solid #b0e0e6;">
                        <i class="fas fa-info-circle me-2"></i> <span style="font-family: 'Montserrat', sans-serif;">Belum ada kos yang ditambahkan.</span>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" style="border-radius: 15px; overflow: hidden; border: 1px solid #eeeeee;">
                            <thead class="table-dark text-center" style="background-color: #343a40; color: white; font-family: 'Montserrat', sans-serif;">
                                <tr>
                                    <th style="font-size: 1.1rem;">Nama Kos</th>
                                    <th style="font-size: 1.1rem;">Alamat</th>
                                    <th style="font-size: 1.1rem;">Harga</th>
                                    <th style="font-size: 1.1rem;">Fasilitas</th>
                                    <th style="font-size: 1.1rem;">Foto</th>
                                    <th style="font-size: 1.1rem;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody style="font-family: 'Montserrat', sans-serif;">
                                @foreach($kosList as $kos)
                                    <tr>
                                        <td style="vertical-align: middle; text-align: center;">{{ $kos->nama_kos }}</td>
                                        <td style="vertical-align: middle; text-align: center;">{{ $kos->alamat }}</td>
                                        <td style="vertical-align: middle; text-align: center;">Rp {{ number_format($kos->harga, 0, ',', '.') }}</td>
                                        <td style="vertical-align: middle; text-align: center;">{{ $kos->fasilitas }}</td>
                                        <td style="vertical-align: middle; text-align: center;">
                                            @if($kos->foto)
                                                <img src="{{ asset('storage/' . $kos->foto) }}" alt="Foto Kos" width="120" class="img-thumbnail rounded" style="border-radius: 10px;">
                                            @else
                                                <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('kos.edit', $kos->id) }}" class="btn btn-warning btn-sm" style="border-radius: 10px;">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('kos.destroy', $kos->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kos ini?')" style="border-radius: 10px;">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                                <a href="{{ route('kos.reviews.index', $kos->id) }}" class="btn btn-info btn-sm" style="border-radius: 10px;">
                                                    <i class="fas fa-comments"></i> Review
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        }
        .img-thumbnail:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }
        .table-hover tbody tr:hover {
            background-color: #f5f5f5 !important;
        }
    </style>
</x-app-layout>
