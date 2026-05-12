<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Penjualan</title>

    <style>
        body{
            font-family: sans-serif;
            font-size: 12px;
            color: #222;
        }

        h2{
            margin-bottom: 5px;
        }

        .info{
            margin-bottom: 15px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td{
            border: 1px solid #ccc;
            padding: 8px;
        }

        th{
            background: #f2f2f2;
            text-align: center;
        }

        .text-right{
            text-align: right;
        }

        .text-center{
            text-align: center;
        }

        .badge-success{
            background: #d1fae5;
            color: #065f46;
            padding: 3px 6px;
            border-radius: 4px;
        }

        .badge-danger{
            background: #fee2e2;
            color: #991b1b;
            padding: 3px 6px;
            border-radius: 4px;
        }

        .footer{
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h2>Daftar Penjualan</h2>

    <div class="info">
        <strong>Tanggal Cetak :</strong>
        {{ now()->format('d M Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>No Faktur</th>
                <th>Nama Pembeli</th>
                <th>Metode</th>
                <th>Status</th>
                <th class="text-right">Grand Total</th>
                <th>Tanggal Penjualan</th>
            </tr>
        </thead>

        <tbody>

            @php
                $totalSemua = 0;
            @endphp

            @foreach($penjualan as $index => $p)

                @php
                    $totalSemua += $p->grand_total;
                @endphp

                <tr>

                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $p->no_faktur }}
                    </td>

                    <td>
                        {{ $p->nama_pembeli ?? '-' }}
                    </td>

                    <td>
                        {{ $p->metode_pembayaran }}
                    </td>

                    <td class="text-center">

                        @if($p->status_penjualan == 'Selesai')

                            <span class="badge-success">
                                {{ $p->status_penjualan }}
                            </span>

                        @else

                            <span class="badge-danger">
                                {{ $p->status_penjualan }}
                            </span>

                        @endif

                    </td>

                    <td class="text-right">
                        Rp {{ number_format($p->grand_total, 0, ',', '.') }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($p->tanggal_penjualan)->format('d M Y H:i') }}
                    </td>

                </tr>

            @endforeach

        </tbody>
    </table>

    <div class="footer">
        Total Semua :
        Rp {{ number_format($totalSemua, 0, ',', '.') }}
    </div>

</body>
</html>