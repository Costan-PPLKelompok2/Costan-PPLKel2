@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Dashboard Pemilik Kos') }}</span>
                    <a href="{{ route('chat.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('chat.owner') }}" method="GET" class="mb-4">
                        <div class="form-group">
                            <label for="owner_id">Masuk sebagai:</label>
                            <select name="owner_id" class="form-control" required>
                                @foreach(App\Models\User_profile::whereHas('ownedKosts')->get() as $owner)
                                    <option value="{{ $owner->id }}" {{ request('owner_id') == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }} ({{ $owner->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Masuk</button>
                    </form>

                    @if(request()->has('owner_id') && $owner = App\Models\User_profile::find(request('owner_id')))
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        Properti Anda
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Kos</th>
                                                        <th>Alamat</th>
                                                        <th>Harga</th>
                                                        <th>Pesan Baru</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($owner->ownedKosts as $kost)
                                                        @php
                                                            $unreadCount = App\Models\Message::where('kost_id', $kost->id)
                                                                ->where('receiver_id', $owner->id)
                                                                ->whereDoesntHave('receiver.notifications', function($q) {
                                                                    $q->whereNotNull('read_at');
                                                                })
                                                                ->count();
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $kost->name }}</td>
                                                            <td>{{ $kost->address }}</td>
                                                            <td>Rp {{ number_format($kost->price, 0, ',', '.') }}</td>
                                                            <td>
                                                                <a href="{{ route('chat.owner.messages', ['kost' => $kost->id, 'owner_id' => $owner->id]) }}" 
                                                                   class="btn btn-primary btn-sm">
                                                                    Lihat Pesan
                                                                    @if($unreadCount > 0)
                                                                        <span class="badge bg-danger">{{ $unreadCount }}</span>
                                                                    @endif
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        Notifikasi Pesan Terbaru
                                    </div>
                                    <div class="card-body">
                                        @if($owner->notifications->count() > 0)
                                            <div class="list-group">
                                                @foreach($owner->notifications->take(5) as $notification)
                                                    <a href="{{ route('chat.owner.messages', ['kost' => $notification->data['kost_id'], 'owner_id' => $owner->id]) }}" 
                                                       class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'active' }}">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h5 class="mb-1">{{ $notification->data['kost_name'] }}</h5>
                                                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        <p class="mb-1">Pesan dari: {{ $notification->data['sender_name'] }}</p>
                                                        <small>{{ $notification->data['message_preview'] }}</small>
                                                    </a>
                                                @endforeach
                                            </div>
                                            
                                            @if($owner->notifications->count() > 5)
                                                <div class="text-center mt-3">
                                                    <a href="{{ route('notifications.all', ['owner_id' => $owner->id]) }}" class="btn btn-sm btn-info">
                                                        Lihat Semua Notifikasi
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            <div class="alert alert-info mb-0">
                                                Tidak ada notifikasi pesan baru saat ini.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection