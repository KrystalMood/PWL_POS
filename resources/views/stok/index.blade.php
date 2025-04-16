@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Stok</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/stok/create_ajax') }}')" class="btn btn-success btn-sm"><i
                        class="fa fa-plus"></i> Tambah</button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="barang_filter">Filter Barang:</label>
                        <select id="barang_filter" class="form-control">
                            <option value="">Semua Barang</option>
                            @foreach(\App\Models\BarangModel::all() as $barang)
                                <option value="{{ $barang->barang_id }}">{{ $barang->barang_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supplier_filter">Filter Supplier:</label>
                        <select id="supplier_filter" class="form-control">
                            <option value="">Semua Supplier</option>
                            @foreach(\App\Models\SupplierModel::all() as $supplier)
                                <option value="{{ $supplier->supplier_id }}">{{ $supplier->supplier_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped" id="tbl-stok">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th>Supplier</th>
                        <th>User</th>
                        <th>Jumlah</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="modal-content">
                <!-- Content will be loaded here -->
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
                },
                error: function (xhr, status, error) {
                    console.log('Ajax error:', xhr, error);
                    alert('Error loading data: ' + xhr.responseText);
                }
            })
        }

        $(document).ready(function() {
            var table = $('#tbl-stok').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('stok/list') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = "{{ csrf_token() }}";
                        d.barang_id = $('#barang_filter').val();
                        d.supplier_id = $('#supplier_filter').val();
                    }
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'stok_tanggal',
                    name: 'stok_tanggal'
                },
                {
                    data: 'barang.barang_nama',
                    name: 'barang.barang_nama'
                },
                {
                    data: 'supplier.supplier_nama',
                    name: 'supplier.supplier_nama'
                },
                {
                    data: 'user.username',
                    name: 'user.username'
                },
                {
                    data: 'stok_jumlah',
                    name: 'stok_jumlah'
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    sortable: false
                }
                ]
            })

            $('#barang_filter, #supplier_filter').on('change', function () {
                table.ajax.reload();
            })
        })
    </script>
@endpush