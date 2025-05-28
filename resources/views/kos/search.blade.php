{{-- resources/views/kos/search.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="bg-slate-50 min-h-screen py-8 md:py-12">

  <div class="max-w-screen-xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-6 md:gap-8 px-4 sm:px-6 lg:px-8">

    {{-- Sidebar Filters --}}
    <aside class="lg:col-span-1">
      <div class="bg-white p-5 md:p-6 rounded-xl shadow-lg space-y-6 sticky top-8">
        <div class="flex items-center space-x-3 border-b border-slate-200 pb-4 mb-4">
          {{-- Heroicon: Funnel --}}
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-primary">
            <path d="M2.667 2.667A2 2 0 0 1 4.667 2h10.666a2 2 0 0 1 2 2v2.5a.5.5 0 0 0 .5.5h.333a.5.5 0 0 1 .5.5v2.666a.5.5 0 0 1-.5.5h-.333a.5.5 0 0 0-.5.5V16a2 2 0 0 1-2 2H4.667a2 2 0 0 1-2-2V3.333A.667.667 0 0 1 2.667 2.667Zm9.083 9.833a.5.5 0 0 0 .5.5h.334a.5.5 0 0 1 .5.5v1.166a.5.5 0 0 1-.5.5h-.334a.5.5 0 0 0-.5.5v1.5H4.667v-1.5a.5.5 0 0 0-.5-.5H3.833a.5.5 0 0 1-.5-.5V12.5a.5.5 0 0 1 .5-.5h.334a.5.5 0 0 0 .5-.5V4.667H11.75v7.833Z" />
          </svg>
          <h3 class="text-xl font-semibold text-slate-800">Filter Pencarian</h3>
        </div>

        <form action="{{ route('kos.search') }}" method="GET" novalidate class="space-y-6">
          {{-- Kata Kunci --}}
          <div class="space-y-1.5">
            <label for="search" class="text-sm font-medium text-slate-700">Kata Kunci</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-400">
                  <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
                </svg>
              </div>
              <input
                type="text"
                name="search"
                id="search"
                value="{{ request('search') }}"
                placeholder="Nama, fasilitas, dll."
                class="block w-full pl-10 pr-3 py-2.5 rounded-lg border border-slate-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/80 focus:border-primary transition duration-150 ease-in-out text-sm placeholder-slate-400"
              />
            </div>
          </div>

          {{-- Lokasi --}}
          <div class="space-y-1.5">
            <label for="location" class="text-sm font-medium text-slate-700">Lokasi</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-400">
                  <path fill-rule="evenodd" d="m9.69 18.933.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 0 0 .281-.145L10.666 18.44A5.741 5.741 0 0 0 12 16.667V5.333A5.333 5.333 0 0 0 6.667 0 5.333 5.333 0 0 0 1.334 5.333V16.667a5.741 5.741 0 0 0 1.333 1.773l.32.36a5.741 5.741 0 0 0 .282.145l.018.008.006.003.003.001A2.25 2.25 0 0 1 10 19Z M10 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" clip-rule="evenodd" />
                </svg>
              </div>
              <input
                type="text"
                name="location"
                id="location"
                value="{{ request('location') }}"
                placeholder="Kota, jalan, atau area"
                class="block w-full pl-10 pr-3 py-2.5 rounded-lg border border-slate-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/80 focus:border-primary transition duration-150 ease-in-out text-sm placeholder-slate-400"
              />
            </div>
          </div>

          {{-- Harga --}}
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-slate-700">Rentang Harga (per bulan)</label>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label for="price_min" class="sr-only">Min Harga</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500 text-sm">Rp</div>
                  <input
                    type="number"
                    name="price_min"
                    id="price_min"
                    value="{{ request('price_min') }}"
                    placeholder="Min"
                    class="mt-1 block w-full pl-9 pr-3 py-2.5 rounded-lg border border-slate-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/80 focus:border-primary transition duration-150 ease-in-out text-sm placeholder-slate-400"
                    min="0"
                  />
                </div>
              </div>
              <div>
                <label for="price_max" class="sr-only">Max Harga</label>
                <div class="relative">
                 <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500 text-sm">Rp</div>
                  <input
                    type="number"
                    name="price_max"
                    id="price_max"
                    value="{{ request('price_max') }}"
                    placeholder="Max"
                    class="mt-1 block w-full pl-9 pr-3 py-2.5 rounded-lg border border-slate-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/80 focus:border-primary transition duration-150 ease-in-out text-sm placeholder-slate-400"
                    min="0"
                  />
                </div>
              </div>
            </div>
          </div>

          {{-- Fasilitas --}}
          <div class="space-y-2">
            <label class="text-sm font-medium text-slate-700">Fasilitas</label>
            <div class="flex flex-wrap gap-2 max-h-48 overflow-y-auto p-1 -m-1"> {{-- Scroll jika fasilitas banyak --}}
              @foreach($facilities as $f)
                @php $sel = in_array($f->id, (array) request('facilities', [])) @endphp
                <label class="inline-flex items-center cursor-pointer group">
                  <input
                    type="checkbox"
                    name="facilities[]"
                    value="{{ $f->id }}"
                    class="sr-only peer"
                    {{ $sel ? 'checked' : '' }}
                  />
                  <span
                    class="
                      px-3 py-1.5 rounded-full text-xs font-medium border transition-all duration-200 ease-in-out
                      {{-- Style untuk kondisi TIDAK TERPILIH (latar putih, teks abu-abu) --}}
                      bg-white text-slate-600 border-slate-300

                      {{-- Style untuk HOVER (saat mouse di atas, belum dipilih) --}}
                      group-hover:bg-slate-100 group-hover:border-slate-400 group-hover:text-slate-800

                      {{-- Style untuk kondisi TERPILIH (peer-checked) --}}
                      peer-checked:text-blue-800      {{-- Warna teks menjadi biru tua --}}
                      peer-checked:font-semibold    {{-- Teks menjadi agak tebal --}}
                      peer-checked:border-blue-500    {{-- Border menjadi biru untuk menandakan terpilih --}}
                      peer-checked:shadow-sm          {{-- Bayangan halus tetap ada --}}
                      {{-- TIDAK ADA peer-checked:bg-* agar latar belakang tetap putih --}}

                      {{-- Style untuk Fokus (Aksesibilitas Keyboard) --}}
                      peer-focus-visible:ring-2 peer-focus-visible:ring-offset-1 peer-focus-visible:ring-blue-600/70
                    "
                  >
                    {{ $f->name }}
                  </span>
                </label>
              @endforeach
            </div>
          </div>

          {{-- Buttons --}}
          <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 pt-4 border-t border-slate-200 mt-5">
            <button
              type="submit"
              class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-primary text-white font-semibold rounded-lg shadow-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/80 transition-colors duration-150 ease-in-out text-sm"
            >
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-2">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
              </svg>
              Cari
            </button>
            <a
              href="{{ route('kos.search') }}"
              class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-slate-100 text-slate-700 font-semibold rounded-lg border border-slate-300 hover:bg-slate-200 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-primary/70 transition-colors duration-150 ease-in-out text-sm shadow-sm"
            >
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-2">
                <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 0 1-9.205-4.593l.012-.016.02-.021-.001.001A5.5 5.5 0 0 1 10.425 2.72a.75.75 0 0 1 .866 1.198l-.002.003-.005.007a4 4 0 0 0 2.358 4.034l.13.049.132.044-.002-.001.005-.002a.75.75 0 0 1 .18.021Zm-2.354-2.353a.75.75 0 1 0-1.06-1.06L9.75 10.19l-1.148-1.147a.75.75 0 1 0-1.06 1.06L8.147 10.75l-1.604 1.603a.75.75 0 0 0 1.06 1.06L9.75 11.81l1.148 1.147a.75.75 0 1 0 1.06-1.06L11.354 10.75l1.604-1.603ZM8.854 2.796a.75.75 0 0 1 0 1.06l-1.856 1.855a.75.75 0 0 1-1.062-1.06L7.795 2.796a.75.75 0 0 1 1.06 0ZM5.75 15.5a.75.75 0 0 0 0-1.5H4.5a.75.75 0 0 0 0 1.5h1.25Z" clip-rule="evenodd" />
                <path d="M3 9.5a.75.75 0 0 1 .75.75v2.018A5.504 5.504 0 0 0 8.46 17.5h3.081a.75.75 0 0 1 0 1.5H8.46A7.005 7.005 0 0 1 1.5 12.268V10.25A.75.75 0 0 1 3 9.5Z" />
              </svg>
              Reset
            </a>
          </div>
        </form>
      </div>
    </aside>

    {{-- Grid Hasil --}}
    <div class="lg:col-span-3 space-y-6 md:space-y-8">
      {{-- Header Hasil Pencarian --}}
      <div class="space-y-3 sm:space-y-4 pb-4 border-b border-slate-200">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">
          Hasil Pencarian
        </h2>

        <div class="flex flex-col gap-y-3 sm:flex-row sm:items-center sm:justify-between">
          {{-- Tombol Bandingkan --}}
          @php
              $compareIds = session('compare', []);
              $compareCount = count($compareIds);
          @endphp
          <div>
            <a href="{{ $compareCount > 0 ? route('kos.compare') : '#' }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-primary text-white font-semibold rounded-lg shadow-sm hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-primary/80 transition-colors duration-150 ease-in-out text-xs sm:text-sm
                      {{ $compareCount > 0 ? '' : 'opacity-50 cursor-not-allowed' }}"
               aria-disabled="{{ $compareCount === 0 }}"
               title="{{ $compareCount === 0 ? 'Pilih kos untuk dibandingkan' : 'Lihat halaman perbandingan' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-2">
                    <path fill-rule="evenodd" d="M10.202 3.562a.75.75 0 0 0-1.404 0L5.902 6.5H3.75a.75.75 0 0 0 0 1.5h2.152l-2.896 2.938a.75.75 0 0 0 1.054 1.068L7 9.06l2.098 3.062a.75.75 0 1 0 1.212-.828L8.648 8.75l3.027-3.063a.75.75 0 0 0-1.054-1.068L7.772 7.439 10.202 3.562ZM16.25 6.5h-2.152l2.896 2.938a.75.75 0 1 1-1.054 1.068L13 9.06l-2.098 3.062a.75.75 0 1 1-1.212-.828L11.352 8.75 8.325 5.688a.75.75 0 0 1 1.054-1.068l2.849 2.823L9.798 3.563a.75.75 0 0 1 1.404 0l2.896 2.937H16.25a.75.75 0 0 1 0 1.5Z" clip-rule="evenodd" />
                </svg>
                Bandingkan (<span id="compare-count-text">{{ $compareCount }}</span>)
            </a>
            @if($kosList->total() > 0) {{-- Tampilkan petunjuk hanya jika ada hasil pencarian --}}
                @if($compareCount === 0)
                    <p class="text-xs text-slate-500 mt-1.5">Centang beberapa kos di bawah untuk mulai membandingkan.</p>
                @elseif($compareCount === 1)
                    <p class="text-xs text-slate-500 mt-1.5">Pilih minimal 1 kos lagi untuk dapat dibandingkan.</p>
                @endif
            @endif
          </div>

          {{-- Jumlah Hasil Ditemukan --}}
          @if ($kosList->total() > 0)
          <span class="text-sm text-slate-600 mt-1 sm:mt-0 self-end sm:self-center">
            Menampilkan <span class="font-semibold text-slate-700">{{ $kosList->firstItem() }}</span> - <span class="font-semibold text-slate-700">{{ $kosList->lastItem() }}</span> dari <span class="font-semibold text-slate-700">{{ $kosList->total() }}</span> kos ditemukan
          </span>
          @endif
        </div>
      </div>
      {{-- Akhir Header Hasil Pencarian --}}


      @if($kosList->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
          @foreach($kosList as $kos)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 ease-in-out overflow-hidden flex flex-col group">
              <a href="{{ route('kos.show', $kos->id) }}" class="block">
                <div class="relative">
                  <img
                    src="{{ $kos->foto ? asset('storage/'.$kos->foto) : asset('images/default.jpg') }}"
                    alt="Foto {{ $kos->nama_kos }}"
                    class="h-56 w-full object-cover transform group-hover:scale-105 transition-transform duration-300 ease-in-out"
                  />
                  <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/50 to-transparent opacity-95 group-hover:opacity-100 transition-opacity duration-300"></div>
                  <div class="absolute bottom-0 left-0 right-0 p-4">
                    <h3 class="text-lg font-semibold text-white leading-tight truncate group-hover:underline">{{ $kos->nama_kos }}</h3>
                    <p class="text-base font-bold text-lime-300">Rp {{ number_format($kos->harga) }}<span class="text-sm font-normal text-slate-200"> / bulan</span></p>
                  </div>
                  {{-- Contoh Badge (opsional) --}}
                  {{-- <span class="absolute top-3 right-3 bg-pink-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md">POPULER</span> --}}
                </div>
              </a>

              <div class="p-4 md:p-5 flex-1 flex flex-col justify-between">
                <div>
                  <p class="text-slate-600 text-sm mb-3 leading-relaxed h-20 overflow-hidden three-line-clamp">
                     {{ Str::limit($kos->deskripsi, 120) }}
                  </p>
                  <div class="text-xs text-slate-500 space-y-1.5 mb-4">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mr-1.5 text-slate-400 flex-shrink-0">
                            <path fill-rule="evenodd" d="M8 16a.5.5 0 0 1-.447-.276L4.832 9.87A5.002 5.002 0 0 1 8 2a5.002 5.002 0 0 1 3.168 7.87l-2.72 5.853A.5.5 0 0 1 8 16Zm0-11a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z" clip-rule="evenodd" />
                        </svg>
                        <span class="truncate" title="{{ $kos->alamat }}">{{ Str::limit($kos->alamat, 45) }}</span>
                    </div>
                    {{-- Contoh info tambahan: --}}
                    {{-- @if($kos->tipe_kos)
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mr-1.5 text-slate-400 flex-shrink-0">
                           <path d="M3 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3Zm0 1h10v3H3V2Zm0 4h10v8H3V6Z"/>
                        </svg>
                        <span>Kos {{ $kos->tipe_kos }}</span>
                    </div>
                    @endif --}}
                  </div>
                </div>

                <div class="mt-auto pt-4 border-t border-slate-100 grid grid-cols-2 gap-3">
                  <a
                    href="{{ route('kos.show', $kos->id) }}"
                    class="col-span-2 sm:col-span-1 inline-flex items-center justify-center text-center px-3 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/80 transition-colors duration-150"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-1.5">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
                    </svg>
                    Lihat Detail
                  </a>
                  <form action="{{ route('kos.compare.toggle', $kos->id) }}" method="POST" class="w-full col-span-2 sm:col-span-1">
                    @csrf
                    @php $inComp = in_array($kos->id, session('compare', [])) @endphp
                    <button
                      type="submit"
                      class="w-full inline-flex items-center justify-center px-3 py-2.5 text-sm font-semibold rounded-lg border transition-colors duration-150
                             {{ $inComp
                                ? 'bg-amber-500 text-white border-amber-500 hover:bg-amber-600 focus:ring-amber-400 shadow-sm'
                                : 'bg-slate-50 text-slate-700 border-slate-300 hover:bg-slate-100 hover:border-slate-400 focus:ring-primary/70'
                             }} focus:outline-none focus:ring-2 focus:ring-offset-2"
                    >
                        @if($inComp)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-1.5">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                            </svg>
                            Hapus dari Perbandingan
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-1.5">
                                <path fill-rule="evenodd" d="M10.202 3.562a.75.75 0 0 0-1.404 0L5.902 6.5H3.75a.75.75 0 0 0 0 1.5h2.152l-2.896 2.938a.75.75 0 0 0 1.054 1.068L7 9.06l2.098 3.062a.75.75 0 1 0 1.212-.828L8.648 8.75l3.027-3.063a.75.75 0 0 0-1.054-1.068L7.772 7.439 10.202 3.562ZM16.25 6.5h-2.152l2.896 2.938a.75.75 0 1 1-1.054 1.068L13 9.06l-2.098 3.062a.75.75 0 1 1-1.212-.828L11.352 8.75 8.325 5.688a.75.75 0 0 1 1.054-1.068l2.849 2.823L9.798 3.563a.75.75 0 0 1 1.404 0l2.896 2.937H16.25a.75.75 0 0 1 0 1.5Z" clip-rule="evenodd" />
                            </svg>
                          Bandingkan
                        @endif
                    </button>
                  </form>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="col-span-full text-center py-12 md:py-20 bg-white rounded-xl shadow-xl">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" class="w-20 h-20 text-slate-400 mx-auto mb-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
          <p class="text-xl md:text-2xl font-semibold text-slate-700 mb-2">Oops! Tidak ada kos yang sesuai.</p>
          <p class="text-slate-500 max-w-md mx-auto">Coba ubah kata kunci atau filter pencarian Anda, atau perluas area pencarian untuk menemukan kos impian Anda.</p>
          <a
              href="{{ route('kos.search') }}"
              class="mt-8 inline-flex items-center px-6 py-3 bg-primary text-white font-semibold rounded-lg shadow-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/80 transition-colors duration-150 ease-in-out text-base"
            >
              Reset Filter & Cari Lagi
            </a>
        </div>
      @endif

      {{-- Pagination --}}
      @if ($kosList->hasPages())
      <div class="mt-8 md:mt-12 flex justify-center">
        {{ $kosList->withQueryString()->links() }}
      </div>
      @endif
    </div>

  </div>
</div>

{{-- CSS tambahan untuk three-line-clamp (jika tidak menggunakan plugin Tailwind Typography) --}}
<style>
    .three-line-clamp {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3; /* number of lines to show */
        -webkit-box-orient: vertical;
    }
</style>
@endsection