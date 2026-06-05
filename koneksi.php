<?php
$host     = "127.0.0.1"; // Gunakan IP ini agar koneksi ke MariaDB lebih stabil di lingkungan lokal
$username = "root";      // Default user MariaDB
$password = "root";          // Sesuaikan dengan password MariaDB Bapak (kosongkan jika tidak pakai password)
$database = "rt_jimpitan";
$port     = 3306;        // Port default MariaDB (bisa diganti misal 3307 jika menggunakan DBngin)

// Muat konfigurasi kustom (misal untuk server production) jika ada
if (file_exists(__DIR__ . '/koneksi_custom.php')) {
    include __DIR__ . '/koneksi_custom.php';
}

$koneksi = mysqli_connect($host, $username, $password, $database, $port);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
