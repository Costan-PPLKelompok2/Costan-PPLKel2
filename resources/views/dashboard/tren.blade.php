@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Tren Pencarian Kos</h2>

    <h4>Tren Berdasarkan Fasilitas</h4>
    <table class="table table-bordered mb-5" id="fasilitasTable">
        <thead>
            <tr>
                <th>Fasilitas</th>
                <th>Total Pencarian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trenFasilitas as $item)
            <tr>
                <td>{{ $item->fasilitas }}</td>
                <td>{{ $item->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Tren Pencarian per Bulan</h4>
    <table class="table table-bordered" id="bulanTable">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Total Pencarian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trenPerBulan as $item)
            <tr>
                <td>{{ $item->bulan }}</td>
                <td>{{ $item->total }}</td>
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
        $('#fasilitasTable').DataTable();
        $('#bulanTable').DataTable();
    });
</script>
@endpush
