<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Produk</title>
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
    <h2>Data Produk</h2>
    <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Produk</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Stok</th>
                <th>Dibuat Pada</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produk as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->id }}</td>
                <td>{{ $item->nama_produk }}</td>
                <td>{{ $item->kategoriRelasi?->nama_kategori ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td class="text-right">{{ $item->stok }}</td>
                <td>{{ $item->created_at?->format('d-m-Y') }}</td>
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