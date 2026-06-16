<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Daftar Suplayer</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 15px; text-align: right; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <h2>DAFTAR MASTER DATA SUPLAYER</h2>
        <p>Tanggal Cetak: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Suplayer</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suplayer as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data->kode_suplayer }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->address }}, {{ $data->city }}</td>
                <td>{{ $data->phone }}</td>
                <td>{{ $data->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh Admin: {{ auth()->user()->name }}
    </div>
</body>
</html>