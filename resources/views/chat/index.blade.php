@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Daftar Kos Tersedia') }}</div>

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

                    <form action="{{ route('chat.index') }}" method="GET" class="mb-4">
                        <div class="form-group">
                            <label for="user_profile_id">Pilih Akun Anda:</label>
                            <select name="user_profile_id" class="form-control" required>
                                @foreach(App\Models\User_profile::where('id', '!=', auth()->id())->get() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Pilih Akun</button>
                    </form>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Kos</th>
                                    <th>Alamat</th>
                                    <th>Harga</th>
                                    <th>Pemilik</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kosts as $kost)
                                <tr>
                                    <td>{{ $kost->name }}</td>
                                    <td>{{ $kost->address }}</td>
                                    <td>Rp {{ number_format($kost->price, 0, ',', '.') }}</td>
                                    <td>{{ $kost->owner->name }}</td>
                                    <td>
                                        @if(request()->has('user_profile_id'))
                                            <a href="{{ route('chat.show', ['kost' => $kost->id, 'user_profile_id' => request('user_profile_id')]) }}" 
                                               class="btn btn-sm btn-primary">Chat dengan Pemilik</a>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>Pilih Akun Dulu</button>
                                        @endif
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
</div>
@endsection