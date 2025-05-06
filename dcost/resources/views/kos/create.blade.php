@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8 bg-white shadow rounded-lg">
  <h2 class="text-2xl font-semibold text-gray-800 mb-6">Tambah Kos</h2>

  @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded">
      {{ session('success') }}
    </div>
  @endif

  <form method="POST" action="{{ route('kos.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div>
      <label for="nama_kos" class="block text-sm font-medium text-gray-700">Nama Kos</label>
      <input 
        type="text" 
        name="nama_kos" 
        id="nama_kos" 
        required
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        value="{{ old('nama_kos') }}" />
    </div>

    <div>
      <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
      <textarea 
        name="deskripsi" 
        id="deskripsi" 
        rows="3" 
        required
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('deskripsi') }}</textarea>
    </div>

    <div>
      <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
      <input 
        type="text" 
        name="alamat" 
        id="alamat" 
        required
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        value="{{ old('alamat') }}" />
    </div>

    <div>
      <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
      <input 
        type="number" 
        name="harga" 
        id="harga" 
        required
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        value="{{ old('harga') }}" />
    </div>

    <div>
      <label for="fasilitas" class="block text-sm font-medium text-gray-700">Fasilitas</label>
      <textarea 
        name="fasilitas" 
        id="fasilitas" 
        rows="2" 
        required
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('fasilitas') }}</textarea>
    </div>

    <div>
      <label for="foto" class="block text-sm font-medium text-gray-700">Foto Kos</label>
      <input 
        type="file" 
        name="foto" 
        id="foto"
        class="mt-1 block w-full text-gray-700" />
    </div>

    {{-- Dropdown Status Ketersediaan --}}
    <div>
      <label for="status_ketersediaan" class="block text-sm font-medium text-gray-700">
        Status Ketersediaan
      </label>
      <select 
        id="status_ketersediaan"
        name="status_ketersediaan"
        required
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
      >
        <option value="1" {{ old('status_ketersediaan')=='1' ? 'selected' : '' }}>
          Tersedia
        </option>
        <option value="0" {{ old('status_ketersediaan')=='0' ? 'selected' : '' }}>
          Penuh
        </option>
      </select>
    </div>

    <div class="pt-4">
      <button 
        type="submit"
        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md
               text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Simpan Kos
      </button>
    </div>
  </form>
</div>
@endsection
