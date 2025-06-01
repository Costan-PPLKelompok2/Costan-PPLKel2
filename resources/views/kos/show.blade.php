{{-- resources/views/kos/show.blade.php --}}
@extends('layouts.navbar')

@section('content')
<div class="container my-5">
  <div class="row">
    <div class="col-12">
      <h1 class="display-4 text-dark mb-4 font-weight-bold">{{ $kos->nama_kos }}</h1>
    </div>
  </div>

  <div class="row">
    {{-- Photo + Details Section --}}
    <div class="col-lg-6 col-md-12 mb-4">
      <div class="card shadow-sm h-100">
        <div class="card-img-top position-relative overflow-hidden" style="height: 300px;">
          <img
            src="{{ $kos->foto ? asset('storage/'.$kos->foto) : asset('images/default.jpg') }}"
            alt="{{ $kos->nama_kos }}"
            class="img-fluid w-100 h-100"
            style="object-fit: cover;"
          >
        </div>
        <div class="card-body">
          <div class="mb-3">
            <h6 class="text-muted mb-2">Deskripsi</h6>
            <p class="text-dark">{{ $kos->deskripsi }}</p>
          </div>
          
          <div class="mb-3">
            <h6 class="text-muted mb-2">Alamat</h6>
            <p class="text-dark">{{ $kos->alamat }}</p>
          </div>
          
          <div class="mb-3">
            <h6 class="text-muted mb-2">Harga</h6>
            <p class="text-dark font-weight-bold text-primary">Rp {{ number_format($kos->harga) }} / bulan</p>
          </div>
          
          <div class="mb-3">
            <h6 class="text-muted mb-2">Status Ketersediaan</h6>
            <span class="badge badge-pill {{ $kos->status_ketersediaan ? 'badge-success' : 'badge-danger' }} px-3 py-2">
              {{ $kos->status_ketersediaan ? 'Tersedia' : 'Penuh' }}
            </span>
          </div>
          
          <div class="mb-3">
            <h6 class="text-muted mb-2">Fasilitas</h6>
            <ul class="list-unstyled">
              @foreach(explode(',', $kos->fasilitas) as $f)
                <li class="mb-1">
                  <i class="fa fa-check-circle text-success mr-2"></i>
                  <span class="text-dark">{{ trim($f) }}</span>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>

    {{-- Map Section --}}
    <div class="col-lg-6 col-md-12 mb-4">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-light">
          <h5 class="card-title mb-0 text-dark">
            <i class="fa fa-map-marker-alt text-primary mr-2"></i>
            Lokasi
          </h5>
        </div>
        <div class="card-body p-0 position-relative">
          @if($kos->latitude && $kos->longitude)
            {{-- Map Container --}}
            <div id="map" style="height: 400px; width: 100%;"></div>
          @else
            {{-- No Location Message --}}
            <div class="d-flex align-items-center justify-content-center" style="height: 400px; background-color: #f8f9fa;">
              <div class="text-center">
                <i class="fa fa-map-marker-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted mb-2">Lokasi Tidak Tersedia</h5>
                <p class="text-muted mb-0">Koordinat lokasi belum diatur untuk kos ini.</p>
                <small class="text-muted">Hubungi pemilik untuk informasi lokasi yang lebih detail.</small>
              </div>
            </div>
          @endif
        </div>
        <div class="card-footer bg-light text-center">
          @if($kos->latitude && $kos->longitude)
            <a
              href="https://www.openstreetmap.org/?mlat={{ $kos->latitude }}&mlon={{ $kos->longitude }}#map=17/{{ $kos->latitude }}/{{ $kos->longitude }}"
              target="_blank"
              class="btn btn-outline-primary btn-sm"
            >
              <i class="fa fa-external-link-alt mr-1"></i>
              Lihat Peta Lebih Besar
            </a>
          @else
            <button class="btn btn-outline-secondary btn-sm" disabled>
              <i class="fa fa-exclamation-circle mr-1"></i>
              Peta Tidak Tersedia
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Additional Actions --}}
  <div class="row mt-4">
    <div class="col-12">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <h5 class="card-title text-dark mb-3">Tertarik dengan kos ini?</h5>
          @if($kos->status_ketersediaan)
            <button class="btn btn-primary btn-lg mr-2">
              <i class="fa fa-phone mr-2"></i>
              Hubungi Pemilik
            </button>
            <button class="btn btn-outline-primary btn-lg">
              <i class="fa fa-bookmark mr-2"></i>
              Simpan
            </button>
          @else
            <button class="btn btn-secondary btn-lg" disabled>
              <i class="fa fa-times mr-2"></i>
              Kos Sedang Penuh
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Custom Styles --}}
<style>
.card {
  border-radius: 12px;
  border: none;
}

.card-img-top {
  border-radius: 12px 12px 0 0;
}

.badge-pill {
  font-size: 0.875rem;
  font-weight: 500;
}

.btn {
  border-radius: 8px;
  font-weight: 500;
}

.display-4 {
  font-size: 2.5rem;
}

@media (max-width: 768px) {
  .display-4 {
    font-size: 2rem;
  }
  
  .btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
  }
}

.list-unstyled li {
  padding: 0.25rem 0;
}

.card-header {
  border-bottom: 1px solid rgba(0,0,0,0.125);
  border-radius: 12px 12px 0 0 !important;
}

.card-footer {
  border-top: 1px solid rgba(0,0,0,0.125);
  border-radius: 0 0 12px 12px !important;
}
</style>

{{-- Leaflet CSS and JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

{{-- Map Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize map if coordinates exist
    @if($kos->latitude && $kos->longitude)
        // Initialize the map with fixed zoom level
        var map = L.map('map', {
            center: [{{ $kos->latitude }}, {{ $kos->longitude }}],
            zoom: 16, // Fixed zoom level
            zoomControl: true,
            scrollWheelZoom: false, // Disable scroll wheel zoom for better UX
            doubleClickZoom: true,
            touchZoom: true
        });

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Add marker for the kos location
        var marker = L.marker([{{ $kos->latitude }}, {{ $kos->longitude }}]).addTo(map);
        
        // Add popup to marker
        marker.bindPopup(`
            <div class="text-center">
                <strong>{{ $kos->nama_kos }}</strong><br>
                <small>{{ $kos->alamat }}</small><br>
                <a href="https://www.openstreetmap.org/?mlat={{ $kos->latitude }}&mlon={{ $kos->longitude }}#map=17/{{ $kos->latitude }}/{{ $kos->longitude }}" target="_blank" class="btn btn-sm btn-primary mt-2 text-white">
                    Buka di OpenStreetMap
                </a>
            </div>
        `).openPopup();

        // Add custom control for better zoom
        var customControl = L.control({position: 'topright'});
        customControl.onAdd = function(map) {
            var div = L.DomUtil.create('div', 'leaflet-control-custom');
            div.innerHTML = `
                <button onclick="map.setView([{{ $kos->latitude }}, {{ $kos->longitude }}], 16)" 
                        class="btn btn-sm btn-light" 
                        title="Reset View"
                        style="margin: 5px;">
                    <i class="fa fa-home"></i>
                </button>
            `;
            return div;
        };
        customControl.addTo(map);

        // Make map variable global for the reset button
        window.map = map;
    @endif
});
</script>
@endsection