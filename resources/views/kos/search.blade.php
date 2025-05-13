{{-- resources/views/kos/search.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">

  {{-- Filters Card --}}
  <div class="bg-white shadow rounded-lg p-6">
    <form action="{{ route('kos.search') }}" method="GET" novalidate class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      @php $facilities = $facilities ?? []; @endphp

      {{-- Keyword --}}
      <div>
        <label for="search" class="block text-sm font-medium text-gray-700">Kata Kunci</label>
        <input
          type="text"
          name="search"
          id="search"
          value="{{ request('search') }}"
          placeholder="Cari kos..."
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
      </div>

      {{-- Location --}}
      <div>
        <label for="location-input" class="block text-sm font-medium text-gray-700">Lokasi</label>
        <input
          type="text"
          name="location"
          id="location-input"
          autocomplete="off"
          placeholder="Cari lokasi..."
          value="{{ request('location') }}"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
        <input type="hidden" name="loc_lat" id="loc_lat" value="{{ request('loc_lat') }}">
        <input type="hidden" name="loc_lng" id="loc_lng" value="{{ request('loc_lng') }}">
      </div>

      {{-- Price Min / Max --}}
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="price_min" class="block text-sm font-medium text-gray-700">Min Harga</label>
          <input
            type="number"
            name="price_min"
            id="price_min"
            value="{{ request('price_min') }}"
            placeholder="0"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          >
        </div>
        <div>
          <label for="price_max" class="block text-sm font-medium text-gray-700">Max Harga</label>
          <input
            type="number"
            name="price_max"
            id="price_max"
            value="{{ request('price_max') }}"
            placeholder="0"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          >
        </div>
      </div>

      {{-- Radius --}}
      <div>
        <label for="radius" class="block text-sm font-medium text-gray-700">Radius (km)</label>
        <select
          id="radius"
          name="radius"
          class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
          <option value="">Pilih</option>
          @foreach([1,3,5,10] as $km)
            <option value="{{ $km }}" {{ request('radius') == (string)$km ? 'selected':'' }}>
              {{ $km }} km
            </option>
          @endforeach
        </select>
      </div>

      {{-- Facilities --}}
      <div class="md:col-span-2 lg:col-span-2">
        <label for="facilities" class="block text-sm font-medium text-gray-700">Fasilitas</label>
        <select
          id="facilities"
          name="facilities[]"
          multiple
          size="4"
          class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          title="Tahan Ctrl/Cmd untuk pilih banyak"
        >
          @foreach($facilities as $f)
            <option
              value="{{ $f->id }}"
              {{ in_array($f->id, (array)request('facilities', [])) ? 'selected':'' }}
            >
              {{ $f->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Buttons --}}
      <div class="flex items-end justify-end space-x-2 md:col-span-2 lg:col-span-4">
        <button
          type="submit"
          class="inline-flex justify-center py-2 px-4 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Cari
        </button>
        <a
          href="{{ route('kos.search') }}"
          class="inline-flex justify-center py-2 px-4 bg-white text-gray-700 text-sm font-medium rounded-md shadow-sm border hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Reset
        </a>
      </div>
    </form>
  </div>

  {{-- Tombol ke halaman Compare --}}
  @php $compare = session('compare', []); @endphp
  @if(count($compare))
    <div class="flex justify-end">
      <a
        href="{{ route('kos.compare') }}"
        class="inline-flex items-center space-x-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md shadow"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <span>Perbandingan ({{ count($compare) }})</span>
      </a>
    </div>
  @endif

  {{-- Results --}}
  <div>
    <h2 class="text-xl font-semibold text-gray-800 mb-4">
      Hasil Pencarian ({{ $kosList->total() }})
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      @forelse($kosList as $kos)
        <div class="bg-white shadow rounded-lg overflow-hidden flex flex-col">
          <img
            src="{{ $kos->foto ? asset('storage/'.$kos->foto) : asset('images/default.jpg') }}"
            alt="{{ $kos->nama_kos }}"
            class="h-48 w-full object-cover"
          >
          <div class="p-4 flex-1 flex flex-col">
            <h5 class="text-lg font-medium text-gray-800">{{ $kos->nama_kos }}</h5>
            <p class="text-gray-600 text-sm mt-1 flex-1">{{ Str::limit($kos->deskripsi, 80) }}</p>
            <ul class="text-gray-700 text-sm mt-2 space-y-1">
              <li><strong>Alamat:</strong> {{ $kos->alamat }}</li>
              <li><strong>Harga:</strong> Rp {{ number_format($kos->harga) }}/bln</li>
              <li>
                <strong>Status:</strong>
                <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded {{ $kos->status_ketersediaan ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                  {{ $kos->status_ketersediaan ? 'Tersedia' : 'Penuh' }}
                </span>
              </li>
            </ul>
            @isset($kos->distance)
              <p class="text-gray-500 text-xs mt-2">Jarak: {{ round($kos->distance,2) }} km</p>
            @endisset

            {{-- Detail Button --}}
            <a
              href="{{ route('kos.show', $kos->id) }}"
              class="mt-4 inline-block text-center py-2 px-4 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700"
            >
              Detail
            </a>

            {{-- Toggle Compare --}}
            <form action="{{ route('kos.compare.toggle', $kos->id) }}" method="POST" class="mt-2">
              @csrf
              @php $inCompare = in_array($kos->id, $compare); @endphp
              <button
                type="submit"
                class="w-full inline-flex justify-center py-2 px-4 text-sm font-medium rounded {{ $inCompare ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }}"
              >
                {{ $inCompare ? '✓ Dibandingkan' : 'Bandingkan' }}
              </button>
            </form>
          </div>
        </div>
      @empty
        <div class="col-span-full">
          <div class="text-center py-10 text-gray-500">
            Tidak ada kos ditemukan sesuai kriteria.
          </div>
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center">
      {{ $kosList->withQueryString()->links() }}
    </div>
  </div>
</div>
@endsection

@push('scripts')
  {{-- Google Places API for Autocomplete --}}
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
  <script>
    function initAutocomplete() {
      const input = document.getElementById('location-input');
      const autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        document.getElementById('loc_lat').value = place.geometry.location.lat();
        document.getElementById('loc_lng').value = place.geometry.location.lng();
      });
    }
    document.addEventListener('DOMContentLoaded', initAutocomplete);
  </script>
@endpush
