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
<?php

$page = $_SERVER['PHP_SELF'];
$sec = "60";
date_default_timezone_set('Asia/Jakarta');

?>
<html>
    <head>
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    </head>
    <body>
    <?php
        echo "Watch the page reload itself in 10 second!<br>";
        echo "Tanggal dan Waktu sekarang adalah " . date("Y-m-d h:i:sa") . "<br>";
    ?>
    
    </body>
$page = $_SERVER['PHP_SELF'];
$sec = "60"; // Halaman akan refresh otomatis setiap 60 detik
$sec = "60";
date_default_timezone_set('Asia/Jakarta');
?>
<html>
<head>
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    <title>Sistem Pengiriman Email Retur Otomatis</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 40px; background-color: #f4f7f6; color: #333; text-align: center; }
        .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: inline-block; }
        h2 { color: #d9534f; }
        .timer { font-weight: bold; color: #0275d8; }
        .footer { margin-top: 20px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔄 Pemantauan Email Retur</h2>
        <p>Sistem sedang mengecek data retur baru untuk dikirimkan ke pelanggan.</p>
        
        <p>Halaman akan memuat ulang dalam <span class="timer">10 detik</span>!</p>
        <p>Waktu Server Sekarang: <strong><?php echo date("Y-m-d H:i:s"); ?></strong></p>
        
        <hr>
        <p style="font-size: 14px;">Status: <span style="color: green;">Aktif & Menunggu Data...</span></p>
    </div>

    <div class="footer">
        © POS Rindu Bunda - Automated Mailer System
    </div>
    <meta http-equiv="refresh" content="<?php echo $sec; ?>;URL='<?php echo $page; ?>'">
</head>
<body>
<?php
    echo "Sistem pengecekan email berjalan...<br>";
    echo "Waktu sekarang: " . date("Y-m-d H:i:sa") . "<br>";
?>
</body>
</html>