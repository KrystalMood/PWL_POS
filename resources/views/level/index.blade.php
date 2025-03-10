@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Level</h3>
        <div class="card-tools">
            <a href="{{ url('/level/create') }}" class="btn btn-primary btn-sm">Tambah Level</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped" id="tbl-level">
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
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#tbl-level').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ url('level/list') }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}"
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
                    searchable: false,
                    sortable: false
                }
            ]
        });
    });
</script>
@endpush
