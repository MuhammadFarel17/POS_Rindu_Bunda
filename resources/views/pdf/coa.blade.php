<!DOCTYPE html>
<html>
<head>
    <title>Daftar Chart of Accounts</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2 f2 f2; }
    </style>
</head>
<body>
    <h2>Daftar Akun (COA)</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Header</th>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coa as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->header_akun }}</td>
                <td>{{ $item->kode_akun }}</td>
                <td>{{ $item->nama_akun }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>