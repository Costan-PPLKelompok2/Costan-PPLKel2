{{-- resources/views/kos/compare.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen">
  <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    {{-- Judul & tombol --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 pb-5 border-b border-slate-200">
      <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Perbandingan Kos</h1>
      <a href="{{ route('kos.search') }}"
         class="mt-4 sm:mt-0 inline-flex items-center justify-center px-5 py-2.5 bg-primary text-white font-semibold rounded-lg shadow-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/80 transition-colors duration-150 ease-in-out text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
        </svg>
        Cari Kos Lagi
      </a>
    </div>

    @if($kosList->isEmpty())
      {{-- Jika belum ada yang dipilih --}}
      <div class="bg-white shadow-xl rounded-xl p-8 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-slate-400 mb-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
        </svg>
        <h2 class="text-xl font-semibold text-slate-700 mb-2">Belum Ada Kos untuk Dibandingkan</h2>
        <p class="text-slate-500 mb-6">Silakan pilih beberapa kos dari halaman pencarian untuk mulai membandingkan.</p>
        <a href="{{ route('kos.search') }}"
           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
          Kembali ke Pencarian
        </a>
      </div>
    @else
      {{-- Tabel perbandingan --}}
      <div class="overflow-x-auto bg-white shadow-xl rounded-xl">
        <table class="min-w-full table-auto">
          <thead class="bg-slate-100 sticky top-0 z-20">
          {{-- Baris Gambar --}}
          <tr>
            <th class="sticky left-0 bg-slate-100 z-30"></th>
            @foreach($kosList as $kos)
              <th class="p-0 border-l border-slate-200">
                <img
                  src="{{ $kos->foto ? asset('storage/'.$kos->foto) : asset('images/default.jpg') }}"
                  alt="{{ $kos->nama_kos }}"
                  class="w-full h-56 object-contain rounded-tl-lg rounded-tr-lg"
                >
              </th>
            @endforeach
          </tr>
            {{-- Baris Judul --}}
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider sticky left-0 bg-slate-100 z-30">
                Fitur
              </th>
              @foreach($kosList as $kos)
                <th class="px-6 py-4 text-left text-sm font-medium text-slate-700 border-l border-slate-200">
                  {{ Str::limit($kos->nama_kos, 22) }}
                  <form action="{{ route('kos.compare.toggle', $kos->id) }}" method="POST" class="inline-block ml-2">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-600">&times;</button>
                  </form>
                </th>
              @endforeach
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-200">
            {{-- Baris Harga --}}
            <tr class="group hover:bg-slate-50">
              <td class="px-6 py-4 font-semibold text-sm text-slate-700 sticky left-0 bg-white z-10">
                Harga
              </td>
              @foreach($kosList as $kos)
                <td class="px-6 py-4 border-l border-slate-200 text-sm text-slate-800">
                  <span class="font-bold text-primary">Rp {{ number_format($kos->harga) }}</span>
                  <span class="text-xs text-slate-500">/bln</span>
                </td>
              @endforeach
            </tr>

            {{-- Baris Status --}}
            <tr class="group hover:bg-slate-50">
              <td class="px-6 py-4 font-semibold text-sm text-slate-700 sticky left-0 bg-white z-10">
                Status
              </td>
              @foreach($kosList as $kos)
                <td class="px-6 py-4 border-l border-slate-200 text-sm">
                  @if($kos->status_ketersediaan)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                      Tersedia
                    </span>
                  @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                      Penuh
                    </span>
                  @endif
                </td>
              @endforeach
            </tr>

            {{-- Baris Fasilitas --}}
            <tr class="group hover:bg-slate-50">
              <td class="px-6 py-4 font-semibold text-sm text-slate-700 align-top sticky left-0 bg-white z-10">
                Fasilitas
              </td>
              @foreach($kosList as $kos)
                <td class="px-6 py-4 border-l border-slate-200 text-sm text-slate-600">
                  @if(trim($kos->fasilitas))
                    <ul class="list-disc list-inside space-y-1">
                      @foreach(explode(',', $kos->fasilitas) as $f)
                        <li>{{ trim($f) }}</li>
                      @endforeach
                    </ul>
                  @else
                    <span class="text-slate-400 italic text-xs">—</span>
                  @endif
                </td>
              @endforeach
            </tr>

            {{-- Baris Aksi --}}
            <tr class="group hover:bg-slate-50">
              <td class="px-6 py-4 font-semibold text-sm text-slate-700 sticky left-0 bg-white z-10">
                Aksi
              </td>
              @foreach($kosList as $kos)
                <td class="px-6 py-4 border-l border-slate-200">
                  <a href="{{ route('kos.show', ['id_kos' => $kos->id]) }}"
                     class="inline-flex items-center px-4 py-2 bg-primary text-white text-xs font-semibold rounded-md shadow-sm hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-primary">
                    Lihat Detail
                  </a>
                </td>
              @endforeach
            </tr>
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>
@endsection
