<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Semua Pembelian</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #999; padding: 8px; text-align: left; }
        table th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; }
        .text-right { text-align: right; }
        .footer { margin-top: 20px; text-align: right; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">LAPORAN TRANSAKSI PEMBELIAN</h2>
        <p style="margin:5px 0;">Toko Rindu Bunda</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Faktur</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Status</th>
                <th class="text-right">Total Tagihan</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->no_faktur }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tgl)->format('d/m/Y') }}</td>
                <td>{{ $item->suplayer->name ?? '-' }}</td>
                <td>{{ strtoupper($item->status) }}</td>
                <td class="text-right">Rp {{ number_format($item->tagihan, 0, ',', '.') }}</td>
            </tr>
            @php $grandTotal += $item->tagihan; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="5" class="text-right">TOTAL KESELURUHAN</td>
                <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>