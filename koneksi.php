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

// Auto-migrate/verify database tables (prevents HTTP 500 on missing tables)
$table_check = mysqli_query($koneksi, "SHOW TABLES LIKE 'kas_setting'");
if ($table_check && mysqli_num_rows($table_check) == 0) {
    mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS `kas_setting` (
        `id` INT PRIMARY KEY,
        `saldo_awal` INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    mysqli_query($koneksi, "INSERT IGNORE INTO `kas_setting` (`id`, `saldo_awal`) VALUES (1, 0)");
}

$table_check_out = mysqli_query($koneksi, "SHOW TABLES LIKE 'kas_pengeluaran'");
if ($table_check_out && mysqli_num_rows($table_check_out) == 0) {
    mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS `kas_pengeluaran` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `tanggal` DATE NOT NULL,
        `keterangan` VARCHAR(255) NOT NULL,
        `nominal` INT NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

$col_check = mysqli_query($koneksi, "SHOW COLUMNS FROM `jimpitan_harian` LIKE 'nominal'");
if ($col_check && mysqli_num_rows($col_check) == 0) {
    mysqli_query($koneksi, "ALTER TABLE `jimpitan_harian` ADD COLUMN `nominal` INT NOT NULL DEFAULT 0");
}
?>
