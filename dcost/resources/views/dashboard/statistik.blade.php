@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Statistik Kos Saya</h2>
    <table class="table table-bordered" id="statistikTable">
        <thead>
            <tr>
                <th>Nama Kos</th>
                <th>Alamat</th>
                <th>Total View</th>
                <th>Favorit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kosList as $kos)
            <tr>
                <td>{{ $kos->nama }}</td>
                <td>{{ $kos->alamat }}</td>
                <td>{{ $kos->views_count }}</td>
                <td>{{ $kos->favorites_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#statistikTable').DataTable();
    });
</script>
@endpush
