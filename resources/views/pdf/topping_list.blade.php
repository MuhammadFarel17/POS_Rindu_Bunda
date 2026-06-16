<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Topping</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <h2>Data Topping</h2>
    <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Topping</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Biaya</th>
                <th>Status</th>
                <th>Dibuat Pada</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topping as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->cost, 0, ',', '.') }}</td>
                <td>
                    <span class="badge {{ $item->is_active ? 'badge-success' : 'badge-warning' }}">
                        {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </td>
                <td>{{ $item->created_at?->format('d-m-Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>