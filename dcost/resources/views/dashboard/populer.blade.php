@section('content')
<div class="container">
    <h2 class="mb-4">Kos Populer</h2>
    <table class="table table-bordered" id="kosPopulerTable">
        <thead>
            <tr>
                <th>Nama Kos</th>
                <th>Alamat</th>
                <th>Jumlah View</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kosPopuler as $kos)
            <tr>
                <td>{{ $kos->nama }}</td>
                <td>{{ $kos->alamat }}</td>
                <td>{{ $kos->views_count }}</td>
                <td>
                    <a href="{{ route('kos.show', $kos->id) }}" class="btn btn-sm btn-primary">Lihat Detail</a>
                </td>
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
        $('#kosPopulerTable').DataTable();
    });
</script>
@endpush
