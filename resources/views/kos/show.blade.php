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
      {{-- existing details card --}}
      @include('kos.partials.details_card')
    </div>

    {{-- Map Section --}}
    <div class="col-lg-6 col-md-12 mb-4">
      {{-- existing map card --}}
      @include('kos.partials.map_card')
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
              <i class="fa fa-heart mr-2"></i>
              Favorite
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

  {{-- Tombol Chat FAB --}}
  @auth
    @if(Auth::id() !== $kos->user_id)
      <a href="{{ route('kos.initiateChat', ['kosId' => $kos->id]) }}"
         title="Chat dengan Pemilik Kos"
         style="position: fixed; z-index: 30; bottom: 24px; right: 24px;"
         class="inline-flex items-center justify-center px-5 py-3 bg-blue-600 dark:bg-blue-700 text-white rounded-full shadow-xl hover:bg-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zm-4 0H9v2h2V9z" clip-rule="evenodd" />
        </svg>
        <span>Chat</span>
      </a>
    @endif
  @else
    <a href="{{ route('login') }}?redirect={{ url()->current() }}"
       title="Login untuk Chat"
       style="position: fixed; z-index: 30; bottom: 24px; right: 24px;"
       class="inline-flex items-center justify-center px-5 py-3 bg-gray-500 dark:bg-gray-600 text-white rounded-full shadow-xl hover:bg-gray-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zm-4 0H9v2h2V9z" clip-rule="evenodd" />
      </svg>
      <span>Login</span>
    </a>
  @endauth
</div>
@endsection
