<!DOCTYPE html>
<html>
<head>
    <title>Data User</title>
</head>
<body>
    <h1>Data User</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Nama</th>
            <th>Level Pengguna</th>
        </tr>
        @if($user)
        <tr>
            <td>{{ $user->user_id }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->nama }}</td>
            <td>{{ $user->level_id }}</td>
        </tr>
        @else
        <tr>
            <td colspan="4">No user found</td>
        </tr>
        @endif
    </table>
    {{-- <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>Level ID</th>
            <th>Count</th>
        </tr>
        <tr>
            <td>2</td>
            <td>{{ $user }}</td>
        </tr>
    </table> --}}
</body>
</html>