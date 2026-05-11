<!-- Simpan isi berikut ke resources/views/pdf/retur-pembelian.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Retur Pembelian</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Daftar Retur Pembelian</h2>

    <table>
        <thead>
            <tr>
                <th>No Retur</th>
                <th>ID Pembelian</th>
                <th>Tanggal</th>
                <th>Total Retur</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($returPembelian as $item)
            <tr>
                <td>{{ $item->no_retur }}</td>
                <td>{{ $item->id_pembelian }}</td>
                <td>{{ $item->tanggal }}</td>
                <td class="text-right">Rp {{ number_format($item->total_retur, 0, ',', '.') }}</td>
                <td>{{ $item->keterangan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>