<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $no_faktur }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .invoice-box { width: 100%; padding: 20px; border: 1px solid #eee; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; }
        table th { background: #f2f2f2; text-align: left; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>INVOICE PEMBAYARAN - RINDU BUNDA</h2>
        <p>
            <strong>No Faktur:</strong> {{ $no_faktur }}<br>
            <strong>Nama Pembeli:</strong> {{ $nama_pembeli }}<br>
            <strong>Tanggal:</strong> {{ $tanggal }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>Barang</th>
                    <th style="text-align: center;">Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->produk->nama_produk ?? $item->nama_barang ?? 'Produk' }}</td>
                    <td style="text-align: center;">
                        {{ $item->jumlah ?? $item->total_barang ?? $item->qty ?? 0 }}
                    </td>
                    <td class="text-right">
                        Rp {{ number_format($item->harga_jual ?? $item->harga ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="text-right">
                        @php
                            $qty = $item->jumlah ?? $item->total_barang ?? $item->qty ?? 0;
                            $harga = $item->harga_jual ?? $item->harga ?? 0;
                            $subtotal = $qty * $harga;
                        @endphp
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
                
                <tr>
                    <td colspan="3" class="text-right"><strong>Total Belanja</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
            </table>

        <p style="margin-top: 30px;">Terima kasih telah berbelanja di Rindu Bunda!</p>
    </div>
</body>
</html>