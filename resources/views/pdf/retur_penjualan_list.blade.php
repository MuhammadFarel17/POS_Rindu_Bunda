<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Retur Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Data Retur Penjualan</h2>
    <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Retur</th>
                <th>No. Faktur Penjualan</th>
                <th>Tanggal Retur</th>
                <th class="text-right">Total Retur</th>
                <th>Alasan Retur</th>
                <th>Admin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($retur_penjualan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->id_retur_penjualan }}</td>
                <td>{{ $item->penjualan?->no_faktur ?? '-' }}</td>
                <td>{{ $item->tanggal_retur?->format('d-m-Y H:i') }}</td>
                <td class="text-right">Rp {{ number_format($item->total_retur, 0, ',', '.') }}</td>
                <td>{{ $item->alasan_retur }}</td>
                <td>{{ $item->user?->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>