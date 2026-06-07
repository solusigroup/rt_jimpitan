<?php
// Tampilkan error jika terjadi masalah di hosting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'auth.php';
check_admin();

include 'koneksi.php';
include 'fungsi_pasaran.php';

$hari_ini = date('Y-m-d');
$weton = getHariPasaran($hari_ini);
$hari = $weton['hari'];
$pasaran = $weton['pasaran'];

// Ambil data warga yang bertugas hari ini
$query = "SELECT w.nama, w.no_wa 
          FROM jadwal_master j
          JOIN warga w ON j.warga_id = w.id
          WHERE j.hari = '$hari' AND j.pasaran = '$pasaran' AND w.status_aktif = 1";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Proses Pengiriman...</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5" style="max-width: 600px;">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white text-center">
                <h5 class="mb-0 fw-bold">Antrean Pesan WhatsApp</h5>
            </div>
            <div class="card-body p-4">

                <?php
                if (mysqli_num_rows($result) > 0) {
                    echo "<p class='text-muted text-center small mb-4'>Silakan klik tombol <strong>Kirim</strong> satu per satu untuk setiap warga di bawah ini:</p>";

                    $no = 1;
                    while ($warga = mysqli_fetch_assoc($result)) {
                        $nama_warga = $warga['nama'];
                        $nomor_wa = preg_replace('/[^0-9]/', '', $warga['no_wa']);

                        // Standarisasi kode nomor HP ke format Indonesia (62)
                        if (substr($nomor_wa, 0, 1) === '0') {
                            $nomor_wa = '62' . substr($nomor_wa, 1);
                        }

                        // Format isi pesan rapi teks tebal dan miring
                        $pesan = "Assalamualaikum Wr. Wb.\n\nMengingatkan kepada Bapak/Ibu *$nama_warga*,\nBerdasarkan jadwal RT, malam ini (*$hari $pasaran*) adalah jadwal Anda untuk bertugas mengambil jimpitan warga.\n\nMohon kerjasamanya demi keamanan lingkungan kita.\nTerima kasih.\n\n— *Pengurus RT*";

                        // Buat tautan resmi WhatsApp Web / App
                        $url_wa = "https://wa.me/" . $nomor_wa . "?text=" . urlencode($pesan);
                        ?>

                        <div
                            class="d-flex justify-content-between align-items-center p-3 mb-2 border rounded bg-white shadow-sm">
                            <div>
                                <span class="text-secondary small">Warga #
                                    <?= $no++; ?>
                                </span>
                                <h6 class="mb-0 fw-bold text-dark">
                                    <?= $nama_warga; ?>
                                </h6>
                            </div>
                            <a href="<?= $url_wa; ?>" target="_blank" class="btn btn-success btn-sm px-3 fw-bold">
                                Kirim ke WA →
                            </a>
                        </div>

                        <?php
                    }
                } else {
                    echo "<div class='alert alert-warning text-center mb-0'>Tidak ada jadwal petugas jimpitan untuk hari ini.</div>";
                }
                ?>

                <div class="text-center mt-4 pt-3 border-top">
                    <a href="admin_pengingat.php" class="btn btn-secondary btn-sm">← Kembali ke Halaman Utama Admin</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>