<!DOCTYPE html>
<html>
<head>
   <title>Membuat QRcode Dengan PHP</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
<h1>Membuat QRcode Generator Dengan PHP</h1>
<form method="post" action="">
   <fieldset>
   <p>Masukkan Nama</p>
   <p><input type="text" name="nama" id="nama" minlength="4" required></p>
   <p>Masukkan Keterangan Berkas</p>
   <p><input type="text" name="keterangan" id="keterangan" minlength="4" required></p>
   <p><input type="submit" name="generate" id="btn_submit" value="Generate QRCode"></p>
   </fieldset>
</form>
<?php
require_once "vendor/autoload.php"; // Atur jalur sesuai dengan lokasi pustaka JWT
use \Firebase\JWT\JWT;
include "vendor/phpqrcode/qrlib.php"; // Include pustaka PHPQRCode
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (isset($_POST['generate'])){
   $nama = $_POST['nama'];
   $keterangan = $_POST['keterangan'];

   $data = array(
      "nama" => $nama,
      "tanggal_diterbit" => date("Y-m-d"), // Tanggal saat ini
      "keterangan_berkas" => $keterangan
   );

   $secret_key = $_ENV["SECRET_KEY"];
   echo $secret_key;
   $token = JWT::encode($data, $secret_key, 'HS256'); // Tambahkan algoritma ('HS256' adalah salah satu pilihan)

   echo "<p class='result'>Token :</p>";
   echo "<p>" . $token . "</p>";

   $tempdir = "img-qrcode/";
   if (!file_exists($tempdir)) mkdir($tempdir, 0755);
   $file_name = date("Ymd") . " - " . $nama . ".png";
   $file_path = $tempdir . $file_name;

   QRcode::png($token, $file_path, "H", 2, 1);
   echo "<p><img src='" . $file_path . "' /></p>";
}
?>
</body>
</html>