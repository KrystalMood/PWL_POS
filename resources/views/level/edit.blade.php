@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Level</h3>
    </div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="{{ url('/level/' . $level->level_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="level_kode">Kode Level</label>
                <input type="text" name="level_kode" id="level_kode" class="form-control" value="{{ old('level_kode', $level->level_kode) }}" placeholder="Masukkan kode level">
            </div>
            <div class="form-group">
                <label for="level_nama">Nama Level</label>
                <input type="text" name="level_nama" id="level_nama" class="form-control" value="{{ old('level_nama', $level->level_nama) }}" placeholder="Masukkan nama level">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ url('/level') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
