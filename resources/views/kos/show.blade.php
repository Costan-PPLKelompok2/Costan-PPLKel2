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

    {{-- Tombol Chat Floating Action Button (FAB) --}}
    @auth
    @if(Auth::id() !== $kos->user_id) {{-- Tampilkan hanya jika user BUKAN pemilik kos ini --}}
      <a href="{{ route('kos.initiateChat', ['kosId' => $kos->id]) }}"
               title="Chat dengan Pemilik Kos"
               style="position: fixed; z-index: 30; bottom: 24px; right: 24px;" {{-- Menggunakan pixel, 24px ~ 1.5rem (Tailwind unit 6) --}}
                                                                                {{-- Untuk 32px ~ 2rem (Tailwind unit 8), gunakan bottom: 32px; left: 32px; --}}
               class="inline-flex items-center justify-center px-5 py-3 
                      bg-blue-600 dark:bg-blue-700 text-white text-sm font-medium rounded-full shadow-xl 
                      hover:bg-blue-700 dark:hover:bg-blue-800 
                      focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 
                      transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zm-4 0H9v2h2V9z" clip-rule="evenodd" />
                </svg>
                <span>Chat</span>
            </a>
        @endif
    @else {{-- Jika user belum login, mungkin tampilkan tombol login atau tidak sama sekali --}}
    <a href="{{ route('login') }}?redirect={{ url()->current() }}"
           title="Login untuk Chat"
           style="position: fixed; z-index: 30; bottom: 24px; right: 24px;" {{-- Menggunakan pixel --}}
           class="inline-flex items-center justify-center px-5 py-3 
                  bg-gray-500 dark:bg-gray-600 text-white text-sm font-medium rounded-full shadow-xl 
                  hover:bg-gray-600 dark:hover:bg-gray-700 
                  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 dark:focus:ring-offset-gray-800 
                  transition ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                 <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zm-4 0H9v2h2V9z" clip-rule="evenodd" />
            </svg>
            <span>Login</span>
        </a>
    @endauth
    {{-- Akhir Tombol Chat FAB --}}

  </div>
</div>
@endsection
