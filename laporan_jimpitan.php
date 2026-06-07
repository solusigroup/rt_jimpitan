<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';
include 'fungsi_pasaran.php';

// 1. Ambil Saldo Awal
$res_setting = mysqli_query($koneksi, "SELECT saldo_awal FROM kas_setting WHERE id = 1");
$data_setting = mysqli_fetch_assoc($res_setting);
$saldo_awal = $data_setting['saldo_awal'];

// 2. Hitung Total Pemasukan (Jimpitan yang sudah selesai)
$q_masuk = "SELECT SUM(nominal) as total_masuk FROM jimpitan_harian WHERE status = 'Sudah Dikerjakan'";
$res_masuk = mysqli_query($koneksi, $q_masuk);
$data_masuk = mysqli_fetch_assoc($res_masuk);
$total_pemasukan = $data_masuk['total_masuk'] ?? 0;

// 3. Hitung Total Pengeluaran
$q_keluar = "SELECT SUM(nominal) as total_keluar FROM kas_pengeluaran";
$res_keluar = mysqli_query($koneksi, $q_keluar);
$data_keluar = mysqli_fetch_assoc($res_keluar);
$total_pengeluaran = $data_keluar['total_keluar'] ?? 0;

// 4. Hitung Saldo Akhir Saat Ini
$saldo_akhir = $saldo_awal + $total_pemasukan - $total_pengeluaran;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Laporan Keuangan Kas Jimpitan RT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <?php include 'menu.php'; ?>

    <div class="container mt-4" style="max-width: 900px;">

        <div class="row mb-4 text-center">
            <div class="col-md-4 mb-2">
                <div class="card bg-white border-0 shadow-sm p-3">
                    <span class="text-secondary small fw-bold text-uppercase">Saldo Awal & Jimpitan</span>
                    <h4 class="text-primary fw-bold mt-1">Rp
                        <?= number_format($saldo_awal + $total_pemasukan, 0, ',', '.'); ?></h4>
                    <small class="text-muted">Awal: Rp <?= number_format($saldo_awal, 0, ',', '.'); ?></small>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card bg-white border-0 shadow-sm p-3">
                    <span class="text-secondary small fw-bold text-uppercase">Total Pengeluaran</span>
                    <h4 class="text-danger fw-bold mt-1">Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?></h4>
                    <small class="text-muted">Dana Terpakai RT</small>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card bg-success text-white border-0 shadow-sm p-3">
                    <span class="text-white-50 small fw-bold text-uppercase">SALDO AKHIR KAS</span>
                    <h3 class="fw-bold mt-1">Rp <?= number_format($saldo_akhir, 0, ',', '.'); ?></h3>
                    <small class="text-white-50">Siap Digunakan</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-bold">Riwayat Uang Jimpitan Masuk</div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover mb-0 small">
                            <thead>
                                <tr>
                                    <th>Tanggal & Weton</th>
                                    <th>Petugas</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res_in = mysqli_query($koneksi, "SELECT jh.tanggal, w.nama, jh.nominal AS nominal_jimpitan FROM jimpitan_harian jh JOIN warga w ON jh.warga_id = w.id WHERE jh.status = 'Sudah Dikerjakan' ORDER BY jh.tanggal DESC, jh.id DESC LIMIT 10");
                                while ($row = mysqli_fetch_assoc($res_in)): 
                                    $weton_data = getHariPasaran($row['tanggal']);
                                    $weton_nama = $weton_data['hari'] . ' ' . $weton_data['pasaran'];
                                    $tanggal_formatted = date('d/m/Y', strtotime($row['tanggal']));
                                ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark mb-0"><?= $tanggal_formatted; ?></div>
                                            <small class="text-secondary" style="font-size: 0.75rem;"><?= $weton_nama; ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($row['nama']); ?></td>
                                        <td class="text-end text-success fw-bold">Rp
                                            <?= number_format($row['nominal_jimpitan'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-danger text-white fw-bold">Riwayat Pengeluaran Kas RT</div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover mb-0 small">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keperluan</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res_out = mysqli_query($koneksi, "SELECT * FROM kas_pengeluaran ORDER BY tanggal DESC LIMIT 10");
                                while ($row = mysqli_fetch_assoc($res_out)): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                        <td><?= $row['keterangan']; ?></td>
                                        <td class="text-end text-danger fw-bold">Rp
                                            <?= number_format($row['nominal'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>