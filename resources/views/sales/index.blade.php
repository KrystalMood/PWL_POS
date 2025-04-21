@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Transaksi Penjualan</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/sales/import') }}')" class="btn btn-info btn-sm"><i class="fa fa-upload"></i> Import</button>
            <a href="{{ route('sales.export_excel') }}" class="btn btn-primary btn-sm"><i class="fa fa-file-excel"></i> Export Excel</a>
            <a href="{{ route('sales.export_pdf') }}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf"></i> Export PDF</a>
            <button onclick="modalAction('{{ url('/sales/create_ajax') }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Tambah</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped" id="tbl-penjualan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Penjualan</th>
                    <th>User</th>
                    <th>Pembeli</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="modal-content">
            <!-- Content akan dimuat via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function modalAction(url) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                $('#modal-content').html(data);
                $('#myModal').modal('show');
            }
        });
    }

    $(document).ready(function () {
        var table = $('#tbl-penjualan').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ url('sales/list') }}",
                type: "POST",
                data: function (d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'penjualan_kode', name: 'penjualan_kode'},
                {data: 'user_nama', name: 'user_nama'},
                {data: 'pembeli', name: 'pembeli'},
                {data: 'penjualan_tanggal', name: 'penjualan_tanggal'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });
</script>
@endpush
