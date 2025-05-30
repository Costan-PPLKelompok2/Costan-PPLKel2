{{-- resources/views/kos/detail.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- ... (notifikasi session success/error) ... --}}

    @if(isset($kos) && $kos)
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            {{-- ... (Bagian Foto Kos, Nama, Harga, Alamat, Deskripsi, Fasilitas, Info Pemilik) ... --}}
            {{-- Pastikan bagian info pemilik menggunakan $kos->pemilik jika sudah diubah di model --}}
            {{-- Contoh: @if($kos->pemilik) <img src="{{ $kos->pemilik->profile_photo_url ... --}}

            <div class="p-6">
                {{-- ... (Detail Kos lainnya seperti Nama, Harga, Alamat, Deskripsi, Fasilitas) ... --}}

                {{-- Informasi Pemilik (Opsional) --}}
                @if($kos->user) {{-- Atau $kos->pemilik jika sudah diganti dan konsisten --}}
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">Informasi Pemilik:</h2>
                    <div class="flex items-center">
                         <img src="{{ $kos->user->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="Foto {{ $kos->user->name }}" class="h-12 w-12 rounded-full object-cover mr-4">
                        <div>
                            <p class="text-gray-800 font-medium">{{ $kos->user->name }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Tombol Aksi --}}
                @auth {{-- Pengguna sudah login --}}
                    @if (Auth::id() == $kos->user_id) {{-- Pengguna adalah PEMILIK kos ini --}}
                        <div class="mt-8 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700" role="alert">
                            <p class="font-bold">Info</p>
                            <p>Ini adalah kos milik Anda. Anda dapat <a href="{{ route('kos.edit', $kos->id) }}" class="font-medium hover:underline">mengedit detail kos ini</a>.</p>
                        </div>
                    @else {{-- Pengguna login tapi BUKAN PEMILIK kos ini (berarti PENYEWA) --}}
                        <div class="mt-8 text-center md:text-left">
                            <a href="{{ route('kos.initiateChat', ['kosId' => $kos->id]) }}"
                               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-gray font-bold py-3 px-6 rounded-lg transition duration-150 ease-in-out text-lg shadow-md hover:shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                                    <path d="M15 7v2a2 2 0 012 2v5a2 2 0 01-2 2h-1v-2a5 5 0 00-5-5H7V7a3 3 0 013-3h4a3 3 0 013 3z" />
                                </svg>
                                Chat dengan Pemilik Kos
                            </a>
                        </div>
                    @endif
                @else {{-- Pengguna BELUM LOGIN --}}
                    <div class="mt-8 text-center md:text-left">
                        <p class="text-gray-700 text-lg">
                            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">Login</a> atau <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-semibold">Daftar</a> untuk dapat menghubungi pemilik kos.
                        </p>
                    </div>
                @endauth

                {{-- ... (Bagian Ulasan) ... --}}
            </div> {{-- Akhir div.p-6 --}}
        </div> {{-- Akhir div.bg-white --}}
    @else {{-- Variabel $kos tidak ada atau null --}}
        <div class="text-center py-12">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Informasi Kos Tidak Ditemukan</h2>
            <p class="text-gray-500 mb-6">Maaf, detail untuk kos yang Anda cari tidak dapat ditampilkan.</p>
            <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                Kembali ke Beranda
            </a>
        </div>
    @endif {{-- Akhir @if(isset($kos) && $kos) --}}
</div> {{-- Akhir div.container --}}
@endsection