{{-- resources/views/kos/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
  <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ $kos->nama_kos }}</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Photo + Details --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <img
        src="{{ $kos->foto ? asset('storage/'.$kos->foto) : asset('images/default.jpg') }}"
        alt="{{ $kos->nama_kos }}"
        class="w-full h-64 object-cover"
      >
      <div class="p-6 space-y-4">
        <p class="text-gray-700">
          <span class="font-medium">Deskripsi:</span> {{ $kos->deskripsi }}
        </p>
        <p class="text-gray-700">
          <span class="font-medium">Alamat:</span> {{ $kos->alamat }}
        </p>
        <p class="text-gray-700">
          <span class="font-medium">Harga:</span> Rp {{ number_format($kos->harga) }} / bln
        </p>
        <p class="text-gray-700">
          <span class="font-medium">Status:</span>
          <span class="inline-block px-2 py-0.5 text-sm font-semibold rounded {{ $kos->status_ketersediaan ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $kos->status_ketersediaan ? 'Tersedia' : 'Penuh' }}
          </span>
        </p>
        <div>
          <span class="font-medium text-gray-800">Fasilitas:</span>
          <ul class="list-disc list-inside text-gray-700 mt-2 space-y-1">
            @foreach(explode(',', $kos->fasilitas) as $f)
              <li>{{ trim($f) }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>

    {{-- Map --}}
    <div class="bg-white shadow rounded-lg overflow-hidden flex flex-col">
      <iframe
        src="https://www.openstreetmap.org/export/embed.html?layer=mapnik&amp;q={{ urlencode($kos->alamat) }}"
        class="w-full flex-1"
        frameborder="0"
        scrolling="no"
      ></iframe>
      <div class="p-4 text-center bg-gray-50">
        <a
          href="https://www.openstreetmap.org/search?query={{ urlencode($kos->alamat) }}"
          target="_blank"
          class="text-indigo-600 hover:underline"
        >
          Lihat Peta Lebih Besar
        </a>
      </div>
    </div>

  </div>
</div>
@endsection
