<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Reservasi {{ $kode_reservasi }}</title>
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
            font-size: 16px;
            font-weight: bold;
        }
        .info {
            margin-top: 10px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="title">Jadwal Reservasi</div>

        <div class="info">
            <strong>Kode Reservasi:</strong> {{ $kode_reservasi }}<br>
            <strong>Nama Pelanggan:</strong> {{ $nama_pelanggan }}<br>
            <strong>Tanggal:</strong> {{ $tanggal }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Meja</th>
                    <th>jumlah orang</th>
                    <th>jam</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->meja }}</td>
                    <td>{{ $item->jumlah_orang }}</td>
                    <td>{{ $item->jam_reservasi }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top: 30px;">Terima kasih atas kepercayaan Anda!</p>
    </div>
</body>
</html>
