{{-- resources/views/kos/manage.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
  <h2 class="text-2xl font-semibold text-gray-800 mb-6">Kelola Kos Saya</h2>

  @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 text-green-800 rounded">
      {{ session('success') }}
    </div>
  @endif

  @if($kosList->isEmpty())
    <p class="text-gray-600">Anda belum menambahkan kos apa pun.</p>
    <a href="{{ route('kos.create') }}" class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Tambah Kos</a>
  @else
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kos</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @foreach($kosList as $kos)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $kos->nama_kos }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($kos->harga) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                @if($kos->status_ketersediaan)
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                @else
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Penuh</span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                <a href="{{ route('kos.edit', $kos->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                <form action="{{ route('kos.destroy', $kos->id) }}" method="POST" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $kosList->links() }}
    </div>
  @endif
</div>
@endsection
