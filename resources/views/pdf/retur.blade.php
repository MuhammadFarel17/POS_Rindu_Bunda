<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Retur {{ $no_faktur }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .invoice-box {
            width: 100%;
            padding: 20px;
            border: 1px solid #eee;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        table th {
            background: #f2f2f2;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #d9534f; /* Warna merah untuk membedakan dengan invoice biasa */
        }
        .info {
            margin-top: 10px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="title">NOTA RETUR PENJUALAN</div>

        <div class="info">
            <strong>No Faktur Asal:</strong> {{ $no_faktur }}<br>
            <strong>ID Retur:</strong> #{{ $id_retur }}<br>
            <strong>Nama Petugas:</strong> {{ $petugas }}<br>
            <strong>Tanggal Retur:</strong> {{ $tanggal }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Keterangan / Alasan Retur</th>
                    <th class="text-right">Total Dana Dikembalikan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ $alasan }}<br>
                        <small style="color: #666;">Proses pengembalian barang atas transaksi faktur {{ $no_faktur }}</small>
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <p style="margin-top: 30px;">Dokumen ini adalah bukti sah pengembalian barang/dana di <strong>Toko Rindu Bunda</strong>. Mohon simpan nota ini sebagai referensi.</p>
    </div>
</body>
</html>