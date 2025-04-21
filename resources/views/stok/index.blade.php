@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Stok</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ route('stok.import') }}')" class="btn btn-info btn-sm"><i class="fa fa-upload"></i> Import</button>
                <a href="{{ route('stok.export_excel') }}" class="btn btn-primary btn-sm"><i class="fa fa-file-excel"></i> Export Excel</a>
                <a href="{{ route('stok.export_pdf') }}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf"></i> Export PDF</a>
                <button onclick="modalAction('{{ url('/stok/create_ajax') }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Tambah</button>
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
                <tbody></tbody>
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
            var tableStok = $('#tbl-stok').DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
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
            });

            $('#barang_filter, #supplier_filter').on('change', function () {
                tableStok.ajax.reload();
            });
            
            $(document).on('submit', '#form-stok', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var method = form.attr('method');
                
                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    success: function(response) {
                        if(response.status) {
                            $('#myModal').modal('hide');
                            tableStok.ajax.reload();
                            toastr.success(response.message || 'Data berhasil diproses');
                        } else {
                            toastr.error(response.message || 'Terjadi kesalahan');
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON?.errors;
                        if(errors) {
                            $.each(errors, function(field, messages) {
                                toastr.error(messages[0]);
                            });
                        } else {
                            toastr.error('Terjadi kesalahan pada server');
                        }
                    }
                });
            });
        });
    </script>
@endpush