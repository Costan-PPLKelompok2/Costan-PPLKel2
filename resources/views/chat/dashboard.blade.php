@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Dashboard Chat Kos') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Mode Penyewa</h5>
                                </div>
                                <div class="card-body">
                                    <p>Sebagai calon penyewa, Anda dapat:</p>
                                    <ul>
                                        <li>Melihat daftar kos yang tersedia</li>
                                        <li>Chat dengan pemilik kos</li>
                                        <li>Lihat riwayat percakapan</li>
                                    </ul>
                                    <a href="{{ route('chat.index') }}" class="btn btn-primary">Mulai Chat sebagai Penyewa</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Mode Pemilik</h5>
                                </div>
                                <div class="card-body">
                                    <p>Sebagai pemilik kos, Anda dapat:</p>
                                    <ul>
                                        <li>Menerima notifikasi pesan masuk</li>
                                        <li>Balas pesan dari calon penyewa</li>
                                        <li>Kelola percakapan dengan mudah</li>
                                    </ul>
                                    <a href="{{ route('chat.owner') }}" class="btn btn-success">Masuk sebagai Pemilik</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Bantuan Penggunaan</h5>
                                </div>
                                <div class="card-body">
                                    <h6>Cara Menggunakan Fitur Chat:</h6>
                                    <ol>
                                        <li>Pilih mode sebagai penyewa atau pemilik kos</li>
                                        <li>Pilih identitas Anda dari daftar</li>
                                        <li>Pilih kos yang ingin Anda tanyakan (mode penyewa) atau lihat pesan masuk (mode pemilik)</li>
                                        <li>Mulai berkomunikasi untuk mendapatkan informasi yang Anda butuhkan</li>
                                    </ol>
                                    <p class="mb-0">Fitur ini memungkinkan komunikasi langsung antara calon penyewa dan pemilik kos untuk mendapatkan informasi yang lebih detail.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection