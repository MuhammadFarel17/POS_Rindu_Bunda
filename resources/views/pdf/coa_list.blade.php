<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data COA</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <h2>Chart of Accounts (COA)</h2>
    <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Header Akun</th>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Dibuat Pada</th>
            </tr>
        </thead>
        <tbody>
            @forelse($coa as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->header_akun }}</td>
                <td>{{ $item->kode_akun }}</td>
                <td>{{ $item->nama_akun }}</td>
                <td>{{ $item->created_at?->format('d-m-Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>