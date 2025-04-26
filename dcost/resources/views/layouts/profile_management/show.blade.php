@extends('profile_management.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow-md">
    <h1 class="text-2xl font-bold mb-4">Profil Saya</h1>

    <div class="flex items-center mb-6">
        @if($user->profile_photo_path)
            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Foto Profil" class="w-24 h-24 rounded-full object-cover">
        @else
            <div class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">
                No Photo
            </div>
        @endif
    </div>

    <div class="space-y-4">
        <div><strong>Nama:</strong> {{ $user->name }}</div>
        <div><strong>Email:</strong> {{ $user->email }}</div>
        <div><strong>No Telepon:</strong> {{ $user->phone_number ?? '-' }}</div>
        <div><strong>Alamat:</strong> {{ $user->address ?? '-' }}</div>
        <div><strong>Preferensi Lokasi:</strong> {{ $user->preferred_location ?? '-' }}</div>
        <div><strong>Preferensi Tipe Kos:</strong> {{ $user->preferred_kos_type ?? '-' }}</div>
        <div><strong>Harga Minimum:</strong> {{ $user->price_min ? 'Rp '.number_format($user->price_min, 2, ',', '.') : '-' }}</div>
        <div><strong>Harga Maksimum:</strong> {{ $user->price_max ? 'Rp '.number_format($user->price_max, 2, ',', '.') : '-' }}</div>
    </div>

    <div class="mt-6">
        <a href="{{ route('profile.edit') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit Profil</a>
    </div>
</div>
@endsection
