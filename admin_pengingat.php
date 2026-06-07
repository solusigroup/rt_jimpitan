<?php
include 'auth.php';
check_admin();
include 'koneksi.php';
include 'fungsi_pasaran.php';

$hari_ini = date('Y-m-d');
initJadwalHarian($koneksi, $hari_ini);

$weton = getHariPasaran($hari_ini);
$hari = $weton['hari'];
$pasaran = $weton['pasaran'];

// Tarik data petugas hari ini untuk ditampilkan di dashboard admin
$query = "SELECT w.id, w.nama, w.no_wa, w.no_rumah, 
                 CASE WHEN jh.status = 'Sudah Dikerjakan' THEN 'Sudah Selesai' ELSE 'Belum Selesai' END AS status_tugas,
                 COALESCE(jh.nominal, 0) AS nominal_jimpitan
          FROM jadwal_master j
          JOIN warga w ON j.warga_id = w.id
          LEFT JOIN jimpitan_harian jh ON w.id = jh.warga_id AND jh.tanggal = '$hari_ini'
          WHERE j.hari = '$hari' AND j.pasaran = '$pasaran' AND w.status_aktif = 1";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Panel Pengingat - Jimpitan RT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <?php include 'menu.php'; ?>

    <div class="container" style="max-width: 700px;">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white text-center py-3">
                <h5 class="mb-0 fw-bold">Kirim Pengingat Jimpitan Warga</h5>
                <small>Jadwal Hari Ini: <strong><?= $hari; ?> <?= $pasaran; ?></strong></small>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info small mb-4">
                    <strong>Petunjuk Pak RT / Pengurus:</strong><br>
                    Klik tombol <strong>"Kirim Pengingat WhatsApp"</strong> di bawah. Sistem akan memproses data dan
                    membuka tab baru langsung ke WhatsApp resmi Bapak dengan teks pesan yang sudah terformat otomatis.
                    Jalur ini 100% aman dari blokir server.
                </div>

                <h6 class="fw-bold mb-3">Daftar Petugas Malam Ini:</h6>

                <?php if (mysqli_num_rows($result) > 0): ?>
                    <div class="list-group mb-4">
                        <?php while ($warga = mysqli_fetch_assoc($result)): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <div>
                                    <h6 class="mb-1 fw-bold"><?= $warga['nama']; ?></h6>
                                    <small class="text-muted">Rumah No. <?= $warga['no_rumah']; ?> | WA:
                                        <?= $warga['no_wa']; ?></small>
                                    <?php if ($warga['status_tugas'] == 'Sudah Selesai'): ?>
                                        <br><span class="badge bg-light text-success border border-success mt-1">💰 Kas: Rp
                                            <?= number_format($warga['nominal_jimpitan'], 0, ',', '.'); ?></span>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <?php if ($warga['status_tugas'] == 'Belum Selesai'): ?>
                                        <a href="entry_jimpitan.php?id=<?= $warga['id']; ?>&token=<?= $kunci_rahasia; ?>"
                                            class="btn btn-outline-primary btn-sm fw-bold me-1">
                                            ✏️ Input Uang
                                        </a>
                                        <span class="badge bg-warning text-dark rounded-pill">Belum Selesai</span>
                                    <?php else: ?>
                                        <span class="badge bg-success rounded-pill">✅ Selesai</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <div class="text-center">
                        <a href="kirim_peringatan.php" target="_blank"
                            class="btn btn-success btn-lg w-100 shadow-sm py-3 fw-bold">
                            🚀 AKTIFKAN & KIRIM PERINGATAN SEKARANG
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center py-4">
                        Tidak ada jadwal warga yang bertugas pada hari <strong><?= $hari; ?>     <?= $pasaran; ?></strong>.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>