<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Gaji - {{ $gaji->no_slip_gaji }}</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .card { border: 1px solid #ccc; padding: 30px; max-width: 400px; margin: auto; border-radius: 10px; }
        .btn { background: #f97316; color: white; padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #ea6c00; }
        p { margin: 8px 0; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Pembayaran Gaji</h2>
        <p><strong>No Slip:</strong> {{ $gaji->no_slip_gaji }}</p>
        <p><strong>Pegawai:</strong> {{ $gaji->pegawai->nama_pegawai ?? '-' }}</p>
        <p><strong>Periode:</strong> {{ $gaji->bulan }} {{ $gaji->tahun }}</p>
        <p><strong>Total:</strong> Rp {{ number_format($gaji->total_diterima, 0, ',', '.') }}</p>
        <br>
        <button class="btn" onclick="bayar()">Bayar Sekarang</button>
    </div>

    <script>
        function bayar() {

            snap.pay('{{ $snap_token }}', {

                onSuccess: function(result) {

                    alert('Pembayaran berhasil!');

                    window.location.href = '/update-status-gaji/{{ $gaji->id }}';
                },

                onPending: function(result) {

                    alert('Pembayaran pending, silakan selesaikan.');
                },

                onError: function(result) {

                    alert('Pembayaran gagal!');
                },

                onClose: function() {

                    alert('Kamu menutup popup pembayaran.');
                }
            });
        }
    </script>
</body>
</html>