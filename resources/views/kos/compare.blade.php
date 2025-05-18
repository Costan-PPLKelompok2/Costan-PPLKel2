{{-- resources/views/kos/compare.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
  <h1 class="text-2xl font-semibold mb-6">Perbandingan Kos</h1>

  @if($kosList->isEmpty())
    <p class="text-gray-600">Belum ada kos yang dipilih untuk dibandingkan.</p>
  @else
    <div class="overflow-x-auto">
      <table class="min-w-full table-auto border-collapse">
        <thead>
          <tr>
            <th class="border px-4 py-2"></th>
            @foreach($kosList as $kos)
              <th class="border px-4 py-2 whitespace-nowrap">
                <div class="flex items-center justify-between">
                  <span class="font-medium">{{ $kos->nama_kos }}</span>
                  <form action="{{ route('kos.compare.toggle', ['id_kos' => $kos->id_kos]) }}" method="POST" class="ml-2">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-800">&times;</button>
                  </form>
                </div>
              </th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="border px-4 py-2 font-medium">Harga</td>
            @foreach($kosList as $kos)
              <td class="border px-4 py-2 whitespace-nowrap">Rp {{ number_format($kos->harga) }}</td>
            @endforeach
          </tr>
          <tr>
            <td class="border px-4 py-2 font-medium">Status</td>
            @foreach($kosList as $kos)
              <td class="border px-4 py-2">
                @if($kos->status_ketersediaan)
                  <span class="text-green-600">Tersedia</span>
                @else
                  <span class="text-red-600">Penuh</span>
                @endif
              </td>
            @endforeach
          </tr>
          <tr>
            <td class="border px-4 py-2 font-medium">Fasilitas</td>
            @foreach($kosList as $kos)
              <td class="border px-4 py-2">
                <ul class="list-disc list-inside space-y-1">
                  @foreach(explode(',', $kos->fasilitas) as $f)
                    <li>{{ trim($f) }}</li>
                  @endforeach
                </ul>
              </td>
            @endforeach
          </tr>
          <tr>
            <td class="border px-4 py-2 font-medium">Lokasi</td>
            @foreach($kosList as $kos)
              <td class="border px-4 py-2 whitespace-nowrap">
                <a
                  href="{{ route('kos.show', ['id_kos' => $kos->id_kos]) }}"
                  class="text-indigo-600 hover:underline"
                >
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
@endsection
