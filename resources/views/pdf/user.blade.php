<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar User</title>

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

        .text-center{
            text-align: center;
        }

        .badge-active{
            background: #d1fae5;
            color: #065f46;
            padding: 3px 6px;
            border-radius: 4px;
        }

        .badge-nonactive{
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

        img{
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <h2>Daftar User</h2>

    <div class="info">
        <strong>Tanggal Cetak :</strong>
        {{ now()->format('d M Y H:i') }}
    </div>

    <table>

        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Username</th>
                <th>Telepon</th>
                <th>Status</th>
                <th>Dibuat</th>
            </tr>
        </thead>

        <tbody>

            @foreach($user as $index => $u)

                <tr>

                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    <td class="text-center">

                        @if($u->photo)

                            <img src="{{ public_path('storage/' . $u->photo) }}">

                        @else

                            -

                        @endif

                    </td>

                    <td>
                        {{ $u->name }}
                    </td>

                    <td>
                        {{ $u->email }}
                    </td>

                    <td>
                        {{ $u->username ?? '-' }}
                    </td>

                    <td>
                        {{ $u->phone ?? '-' }}
                    </td>

                    <td class="text-center">

                        @if($u->is_active)

                            <span class="badge-active">
                                Aktif
                            </span>

                        @else

                            <span class="badge-nonactive">
                                Nonaktif
                            </span>

                        @endif

                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($u->created_at)->format('d M Y H:i') }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

    <div class="footer">
        Total User :
        {{ count($user) }}
    </div>

</body>
</html>