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
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0 text-dark">
            <i class="fa fa-map-marker-alt text-primary mr-2"></i>
            Lokasi
          </h5>
          {{-- Route Controls --}}
          <button id="getRouteBtn" class="btn btn-primary btn-sm" onclick="getRoute()">
            <i class="fa fa-route mr-1"></i>
            Tampilkan Rute
          </button>
          <button id="clearRouteBtn" class="btn btn-secondary btn-sm" onclick="clearRoute()" style="display: none;">
            <i class="fa fa-times mr-1"></i>
            Hapus Rute
          </button>
        </div>
        <div class="card-body p-0 position-relative">
          @if($kos->latitude && $kos->longitude)
            {{-- Map Container --}}
            <div id="map" style="height: 800px; width: 100%;"></div>
            
            {{-- Loading Indicator --}}
            <div id="loadingIndicator" class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1001; display: none;">
              <div class="bg-white p-3 rounded shadow">
                <div class="text-center">
                  <div class="spinner-border spinner-border-sm text-primary mr-2" role="status"></div>
                  <span>Mencari rute...</span>
                </div>
              </div>
            </div>
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
        // Global variables
        let map;
        let userLocationMarker;
        let kosLocationMarker;
        let routeLayer;
        let userLocation = null;
        const kosLocation = [{{ $kos->latitude }}, {{ $kos->longitude }}];

        // Initialize the map with fixed zoom level
        map = L.map('map', {
            center: kosLocation,
            zoom: 14, // Slightly zoomed out to show more area
            zoomControl: true,
            scrollWheelZoom: true,
            doubleClickZoom: true,
            touchZoom: true
        });

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(map);

        // Custom icons
        const kosIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const userIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Add marker for the kos location
        kosLocationMarker = L.marker(kosLocation, {icon: kosIcon}).addTo(map);
        kosLocationMarker.bindPopup(`
            <div class="text-center">
                <strong>🏠 {{ $kos->nama_kos }}</strong><br>
                <small>📍 {{ $kos->alamat }}</small><br>
                <small class="text-muted">Tujuan</small>
            </div>
        `);

        // Function to get user's current location
        function getUserLocation() {
            return new Promise((resolve, reject) => {
                if (!navigator.geolocation) {
                    reject(new Error('Geolocation tidak didukung oleh browser ini.'));
                    return;
                }

                const options = {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 60000
                };

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const coords = [position.coords.latitude, position.coords.longitude];
                        resolve(coords);
                    },
                    (error) => {
                        let message = 'Gagal mendapatkan lokasi: ';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                message += 'Izin akses lokasi ditolak.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                message += 'Informasi lokasi tidak tersedia.';
                                break;
                            case error.TIMEOUT:
                                message += 'Waktu habis saat mencari lokasi.';
                                break;
                            default:
                                message += 'Error tidak dikenal.';
                                break;
                        }
                        reject(new Error(message));
                    },
                    options
                );
            });
        }

        // Function to get route using OSRM
        async function getRouteFromOSRM(start, end) {
            const url = `https://router.project-osrm.org/route/v1/driving/${start[1]},${start[0]};${end[1]},${end[0]}?overview=full&geometries=geojson`;
            
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                
                if (data.routes && data.routes.length > 0) {
                    return data.routes[0];
                } else {
                    throw new Error('Tidak ada rute yang ditemukan');
                }
            } catch (error) {
                throw new Error(`Gagal mendapatkan rute: ${error.message}`);
            }
        }

        // Function to format distance and duration
        function formatDistance(meters) {
            if (meters < 1000) {
                return `${Math.round(meters)} m`;
            } else {
                return `${(meters / 1000).toFixed(1)} km`;
            }
        }

        function formatDuration(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            
            if (hours > 0) {
                return `${hours} jam ${minutes} menit`;
            } else {
                return `${minutes} menit`;
            }
        }

        // Main function to get and display route
        window.getRoute = async function() {
            const loadingIndicator = document.getElementById('loadingIndicator');
            const getRouteBtn = document.getElementById('getRouteBtn');
            const clearRouteBtn = document.getElementById('clearRouteBtn');

            try {
                // Show loading
                loadingIndicator.style.display = 'block';
                getRouteBtn.disabled = true;

                // Get user location if not already obtained
                if (!userLocation) {
                    userLocation = await getUserLocation();
                    
                    // Add user location marker
                    if (userLocationMarker) {
                        map.removeLayer(userLocationMarker);
                    }
                    userLocationMarker = L.marker(userLocation, {icon: userIcon}).addTo(map);
                    userLocationMarker.bindPopup(`
                        <div class="text-center">
                            <strong>📍 Lokasi Anda</strong><br>
                            <small class="text-muted">Titik awal</small>
                        </div>
                    `);
                }

                // Get route from OSRM
                const route = await getRouteFromOSRM(userLocation, kosLocation);

                // Clear existing route
                if (routeLayer) {
                    map.removeLayer(routeLayer);
                }

                // Add route to map
                routeLayer = L.geoJSON(route.geometry, {
                    style: {
                        color: '#b0b435',
                        weight: 5,
                        opacity: 0.8
                    }
                }).addTo(map);

                // Add route info popup
                const distance = formatDistance(route.distance);
                const duration = formatDuration(route.duration);
                
                routeInfoPopup = L.popup({
                  closeButton: false,
                  autoClose: false,
                  closeOnClick: false,
                  className: 'route-info-popup'
                })
                .setLatLng([(userLocation[0] + kosLocation[0]) / 2, (userLocation[1] + kosLocation[1]) / 2])
                .setContent(`
                    <div class="text-center">
                        <strong>🛣️ Informasi Rute</strong><br>
                        <small>📏 Jarak: ${distance}</small><br>
                        <small>⏱️ Estimasi: ${duration}</small>
                    </div>
                `)
                .openOn(map);

                // Fit map to show both markers and route
                const group = new L.featureGroup([userLocationMarker, kosLocationMarker, routeLayer]);
                map.fitBounds(group.getBounds().pad(0.1));

                // Update button states
                getRouteBtn.style.display = 'none';
                clearRouteBtn.style.display = 'block';

            } catch (error) {
                alert(error.message);
                console.error('Error getting route:', error);
            } finally {
                // Hide loading
                loadingIndicator.style.display = 'none';
                getRouteBtn.disabled = false;
            }
        };

        // Function to clear route
        window.clearRoute = function() {
            if (routeLayer) {
                map.removeLayer(routeLayer);
                routeLayer = null;
            }
            
            if (userLocationMarker) {
                map.removeLayer(userLocationMarker);
                userLocationMarker = null;
            }

            if (routeInfoPopup) {
                map.closePopup(routeInfoPopup);
                routeInfoPopup = null;
            }
            userLocation = null;
            
            // Reset map view to kos location
            map.setView(kosLocation, 14);
            
            // Update button states
            document.getElementById('getRouteBtn').style.display = 'block';
            document.getElementById('clearRouteBtn').style.display = 'none';
        };

        // Add custom control for reset view
        const customControl = L.control({position: 'topright'});
        customControl.onAdd = function(map) {
            const div = L.DomUtil.create('div', 'leaflet-control-custom');
            div.innerHTML = `
                <button onclick="map.setView([{{ $kos->latitude }}, {{ $kos->longitude }}], 14)" 
                        class="btn btn-sm btn-light" 
                        title="Reset View"
                        style="margin: 5px;">
                    <i class="fa fa-undo"></i>
                </button>
            `;
            return div;
        };
        customControl.addTo(map);

        // Make map variable global
        window.map = map;
    @endif
});
</script>
@endsection