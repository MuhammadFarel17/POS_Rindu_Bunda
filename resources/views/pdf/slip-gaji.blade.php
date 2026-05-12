<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; margin: 0; padding: 0; }
        .box { border: 1px solid #ccc; padding: 20px; max-width: 600px; margin: auto; }
        h2 { text-align: center; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #f0f0f0; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        
        /* Modifikasi di sini: page-break hanya terjadi jika BUKAN data terakhir */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    @foreach($data as $item)
        <div class="box">
            <h2>SLIP GAJI</h2>
            <p><strong>No Slip:</strong> {{ $item['no_slip_gaji'] }}</p>
            <p><strong>Nama Pegawai:</strong> {{ $item['nama_pegawai'] }}</p>
            <p><strong>Jabatan:</strong> {{ $item['jabatan'] }}</p>
            <p><strong>Periode:</strong> {{ $item['bulan'] }} {{ $item['tahun'] }}</p>

            <table>
                <tr>
                    <th>Komponen</th>
                    <th class="text-right">Nominal</th>
                </tr>
                <tr>
                    <td>Gaji Pokok</td>
                    <td class="text-right">Rp {{ number_format($item['gaji_pokok'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Tunjangan</td>
                    <td class="text-right">Rp {{ number_format($item['total_tunjangan'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Potongan</td>
                    <td class="text-right">Rp {{ number_format($item['total_potongan'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="bold">Gaji Bersih</td>
                    <td class="text-right bold">Rp {{ number_format($item['total_diterima'], 0, ',', '.') }}</td>
                </tr>
            </table>

            <p style="margin-top:20px;">Terima kasih.</p>
        </div>
        
        {{-- Logika: Jika ini bukan data terakhir dalam list, baru berikan page-break --}}
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>