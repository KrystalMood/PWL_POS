@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('user/create') }}">TAMBAH</a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_user">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>USERNAME</th>
                        <th>NAMA</th>
                        <th>LEVEL PENGGUNA</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function () {
            var dataUser = $('#table_user').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('user/list') }}",
                    'dataType': 'json',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                    }, {
                        data: 'username',
                        className: "",
                        orderable: true,
                        searchable: true,
                    }, {
                        data: 'nama',
                        className: "",
                        orderable: true,
                        searchable: true,
                    }, {
                        data: 'level.level_nama',
                        className: "",
                        orderable: false,
                        searchable: false,
                    }, {
                        data: 'aksi',
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                    }
                ]
            });
        });
    </script>
@endpush