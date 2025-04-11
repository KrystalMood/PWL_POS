@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Level</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/level/import') }}')" class="btn btn-info btn-sm"><i class="fa fa-upload"></i> Import</button>
                <a href="{{ route('level.export') }}" class="btn btn-primary btn-sm"><i class="fa fa-file-excel"></i> Export</a>
                <button onclick="modalAction('{{ url('/level/create_ajax') }}')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Tambah</button>
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
                        <label for="level_filter">Filter Level:</label>
                        <select id="level_filter" class="form-control">
                            <option value="">Semua Level</option>
                            @foreach(\App\Models\LevelModel::all() as $level)
                                <option value="{{ $level->level_id }}">{{ $level->level_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover" id="tbl-level">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Level</th>
                        <th>Nama Level</th>
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
            var table = $('#tbl-level').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('level/list') }}",
                    type: "POST",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.level_id = $('#level_filter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'level_kode',
                        name: 'level_kode'
                    },
                    {
                        data: 'level_nama',
                        name: 'level_nama'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            
            $('#level_filter').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
