<?php
$page = $_SERVER['PHP_SELF'];
$sec = "10"; // Halaman akan refresh otomatis setiap 10 detik agar sinkron dengan sistem pengiriman
date_default_timezone_set('Asia/Jakarta');
?>
<html>
<head>
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    <title>Sistem Pengiriman Email Pembelian Otomatis</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 40px; background-color: #f4f7f6; color: #333; text-align: center; }
        .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: inline-block; min-width: 400px; }
        h2 { color: #0275d8; } /* Warna biru agar sesuai dengan tema tombol info/pembelian */
        .timer { font-weight: bold; color: #d9534f; }
        .footer { margin-top: 20px; font-size: 12px; color: #777; }
        .status-box { background-color: #e9f5ff; padding: 10px; border-radius: 8px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔄 Pemantauan Email Pembelian</h2>
        <p>Sistem sedang mengecek data <strong>pembelian baru</strong> untuk dikirimkan ke supplier.</p>
        
        <div class="status-box">
            <p>Halaman akan memuat ulang dalam <span class="timer"><?php echo $sec; ?> detik</span>!</p>
            <p>Waktu Server Sekarang: <strong><?php echo date("Y-m-d H:i:s"); ?></strong></p>
        </div>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 14px;">Status Antrean: <span style="color: green; font-weight: bold;">Aktif & Memindai Tabel Pembelian...</span></p>
    </div>

    <div class="footer">
        © POS Rindu Bunda - Automated Purchase Mailer System
    </div>
</body>
</html>