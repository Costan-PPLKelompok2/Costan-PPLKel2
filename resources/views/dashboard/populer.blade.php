@section('content')
<div class="container py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Kos Populer</h2>
            <div class="text-sm text-gray-600">
                Menampilkan 10 kos dengan pengunjung terbanyak
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-hover w-full" id="kosPopulerTable">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-3">Nama Kos</th>
                        <th class="px-4 py-3">Alamat</th>
                        <th class="px-4 py-3">Pemilik</th>
                        <th class="px-4 py-3">Jumlah View</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kosPopuler as $kos)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $kos->nama }}</td>
                        <td class="px-4 py-3">{{ $kos->alamat }}</td>
                        <td class="px-4 py-3">{{ $kos->pemilik->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                {{ number_format($kos->views_count) }} views
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('kos.show', $kos->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#kosPopulerTable').DataTable({
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada data yang ditemukan",
                info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data yang tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            pageLength: 10,
            order: [[3, 'desc']]
        });
    });
</script>
@endpush
