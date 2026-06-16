<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="60;URL='{{ route('proses.email.reservasi') }}'">
    <title>Sistem Pengiriman Email</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding-top: 50px; background: #fdfdfd; }
        .box { border: 2px solid #007bff; display: inline-block; padding: 30px; background: white; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="box">
        <h2 style="color: #007bff;">⏳ Sedang Memproses Email...</h2>
        <p>Halaman akan refresh otomatis dalam <strong>60 detik</strong> untuk menjaga kuota Mailtrap.</p>
        <p>Waktu Server: <strong>{{ date('H:i:s') }} WIB</strong></p>
    </div>
</body>
</html>