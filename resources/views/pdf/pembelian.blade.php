<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Pembelian {{ $no_faktur }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
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
            padding: 10px;
        }
        table th {
            background: #f8f9fa;
            text-align: left;
            text-transform: uppercase;
            font-size: 10px;
        }
        .text-right { text-align: right; }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 2px solid #337ab7;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            border: none;
            margin-bottom: 20px;
        }
        .info-table td {
            border: none;
            padding: 2px 0;
            vertical-align: top;
        }
        .badge {
            padding: 3px 8px;
            background: #e9ecef;
            border-radius: 4px;
            text-transform: uppercase;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="title">NOTA PEMBELIAN BARANG</div>

        <table class="info-table">
            <tr>
                <td style="width: 15%;"><strong>No. Faktur</strong></td>
                <td style="width: 35%;">: {{ $no_faktur }}</td>
                <td style="width: 15%;"><strong>Tanggal</strong></td>
                <td style="width: 35%;">: {{ \Carbon\Carbon::parse($tgl)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Supplier ID</strong></td>
                <td>: {{ $kode_suplayer }}</td>
                <td><strong>Status</strong></td>
                <td>: <span class="badge">{{ $status }}</span></td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Deskripsi Transaksi</th>
                    <th class="text-right" style="width: 30%;">Total Tagihan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Pembelian stok barang inventaris<br>
                        <small style="color: #666;">ID Transaksi Sistem: #{{ $id }}</small>
                    </td>
                    <td class="text-right" style="vertical-align: middle; font-size: 14px;">
                        <strong>Rp {{ number_format($tagihan, 0, ',', '.') }}</strong>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-right">TOTAL PEMBAYARAN</th>
                    <th class="text-right" style="background: #337ab7; color: white;">
                        Rp {{ number_format($tagihan, 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top: 40px;">
            <p>Catatan:</p>
            <ul style="color: #666; font-size: 11px;">
                <li>Dokumen ini dicetak otomatis dan merupakan bukti pembelian yang sah di <strong>Toko Rindu Bunda</strong>.</li>
                <li>Harap verifikasi barang yang diterima sesuai dengan tagihan yang tertera.</li>
                <li>Waktu Update Terakhir: {{ \Carbon\Carbon::parse($updated_at)->format('d/m/Y H:i:s') }}</li>
            </ul>
        </div>
    </div>
</body>
</html>