<?php
$page = $_SERVER['PHP_SELF'];
$sec = "60";
date_default_timezone_set('Asia/Jakarta');
?>
<html>
<head>
    <meta http-equiv="refresh" content="<?php echo $sec; ?>;URL='<?php echo $page; ?>'">
</head>
<body>
<?php
    echo "Sistem pengecekan email berjalan...<br>";
    echo "Waktu sekarang: " . date("Y-m-d H:i:sa") . "<br>";
?>
</body>
</html>