@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Kos Populer</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Kos</th>
                <th>Alamat</th>
                <th>Harga</th>
                <th>Jumlah Views</th>
            </tr>
        </thead>
        <tbody>
            @foreach($popularKos as $kos)
            <tr>
                <td>{{ $kos->name }}</td>
                <td>{{ $kos->address }}</td>
                <td>{{ $kos->price }}</td>
                <td>{{ $kos->views }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection