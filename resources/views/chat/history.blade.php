@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Riwayat Percakapan') }} - {{ $users_profile->name }}</span>
                    <a href="{{ route('chat.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($kosts->isEmpty())
                        <div class="alert alert-info">
                            Anda belum memiliki percakapan dengan pemilik kos manapun.
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($kosts as $kost)
                                <a href="{{ route('chat.show', ['kost' => $kost->id, 'user_profile_id' => $users_profile->id]) }}" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $kost->name }}</h5>
                                        <small>{{ $kost->latest_message ? $kost->latest_message->created_at->diffForHumans() : 'N/A' }}</small>
                                    </div>
                                    <p class="mb-1">
                                        <strong>Pemilik:</strong> {{ $kost->owner->name }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Pesan terakhir:</strong> 
                                        {{ $kost->latest_message ? 
                                            (strlen($kost->latest_message->message) > 100 ? 
                                                substr($kost->latest_message->message, 0, 100) . '...' : 
                                                $kost->latest_message->message) : 
                                            'Tidak ada pesan' }}
                                    </p>
                                    <small>
                                        <strong>Dari:</strong> 
                                        {{ $kost->latest_message ? 
                                            ($kost->latest_message->sender_id == $users_profile->id ? 
                                                'Anda' : $kost->latest_message->sender->name) : 
                                            'N/A' }}
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection