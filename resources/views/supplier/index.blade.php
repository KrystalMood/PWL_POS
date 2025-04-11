@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Supplier</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/supplier/import') }}')" class="btn btn-info btn-sm"><i class="fa fa-upload"></i> Import</button>
                <a href="{{ route('supplier.export') }}" class="btn btn-primary btn-sm"><i class="fa fa-file-excel"></i> Export</a>
                <button onclick="modalAction('{{ url('/supplier/create_ajax') }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Tambah</button>
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

            <table class="table table-bordered table-striped table-hover" id="tbl-supplier">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Supplier</th>
                        <th>Nama Supplier</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
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
                    console.error(error);
                }
            });
        }

        $(document).ready(function() {
            var table = $('#tbl-supplier').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('supplier/list') }}",
                    type: "POST",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
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
                        data: 'supplier_kode',
                        name: 'supplier_kode'
                    },
                    {
                        data: 'supplier_nama',
                        name: 'supplier_nama'
                    },
                    {
                        data: 'supplier_alamat',
                        name: 'supplier_alamat'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        sortable: false
                    }
                ]
            });
            
            $('#supplier_filter').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
