<!DOCTYPE html>
<html>
<head>
    <title>Data User</title>
</head>
<body>
    <h1>Data User</h1>
    <a href="/user/tambah">Tambah User</a>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <td>ID</td>
            <td>Username</td>
            <td>Nama</td>
            <td>Level ID</td>
            <td>Aksi</td>
        </tr>
        @foreach ($user as $d )
        
            <tr>
                <td>{{ $d->user_id }}</td>
                <td>{{ $d->username }}</td>
                <td>{{ $d->nama }}</td>
                <td>{{ $d->level_id }}</td>
                <td>
                    <a href="/user/ubah/{{ $d->user_id }}">Edit</a>
                    <a href="/user/hapus/{{ $d->user_id }}" onclick="return confirm('Apakah anda yakin?')">Delete</a>
                </td>
            </tr>
        @endforeach

    </table>
</body>
</html>