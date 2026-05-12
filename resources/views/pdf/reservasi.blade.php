<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Reservasi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

<h2>Data Reservasi</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Pelanggan</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Jumlah Orang</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reservasi as $item)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $item->kode_reservasi }}</td>
    <td>{{ $item->nama_pelanggan }}</td>
    <td>{{ $item->tanggal_reservasi }}</td>
    <td>{{ $item->jam_reservasi }}</td>
    <td>{{ $item->jumlah_orang }}</td>
</tr>
@endforeach
    </tbody>
</table>

</body>
</html>