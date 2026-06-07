<?php
include 'auth.php';
check_admin();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

// A. Proses Update Saldo Awal
if (isset($_POST['update_saldo_awal'])) {
    $saldo_awal = preg_replace('/[^0-9]/', '', $_POST['saldo_awal']);
    mysqli_query($koneksi, "UPDATE kas_setting SET saldo_awal = '$saldo_awal' WHERE id = 1");
    header("Location: admin_pengeluaran.php?token=$kunci_rahasia");
}

// B. Proses Tambah Pengeluaran
if (isset($_POST['tambah_pengeluaran'])) {
    $tanggal = $_POST['tanggal'];
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $nominal = preg_replace('/[^0-9]/', '', $_POST['nominal']);
    mysqli_query($koneksi, "INSERT INTO kas_pengeluaran (tanggal, keterangan, nominal) VALUES ('$tanggal', '$keterangan', '$nominal')");
    header("Location: admin_pengeluaran.php?token=$kunci_rahasia");
}

// C. Proses Hapus Pengeluaran
if (isset($_GET['hapus_id'])) {
    $id_hapus = $_GET['hapus_id'];
    mysqli_query($koneksi, "DELETE FROM kas_pengeluaran WHERE id = '$id_hapus'");
    header("Location: admin_pengeluaran.php?token=$kunci_rahasia");
}

// Tarik data saldo awal saat ini
$res_setting = mysqli_query($koneksi, "SELECT saldo_awal FROM kas_setting WHERE id = 1");
$data_setting = mysqli_fetch_assoc($res_setting);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Kelola Pengeluaran Kas - RT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <?php include 'menu.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-dark text-white fw-bold">Atur Saldo Awal Kas</div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="saldo_awal" class="form-control"
                                    value="<?= $data_setting['saldo_awal']; ?>" required>
                                <button type="submit" name="update_saldo_awal" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-danger text-white fw-bold">Catat Pengeluaran Baru</div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-2">
                                <label class="small fw-bold">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>"
                                    required>
                            </div>
                            <div class="mb-2">
                                <label class="small fw-bold">Keperluan / Keterangan</label>
                                <input type="text" name="keterangan" class="form-control"
                                    placeholder="Misal: Beli Lampu Pos Ronda" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold">Nominal Pengeluaran (Rp)</label>
                                <input type="number" name="nominal" class="form-control" placeholder="Contoh: 75000"
                                    required>
                            </div>
                            <button type="submit" name="tambah_pengeluaran" class="btn btn-danger w-100 fw-bold">Simpan
                                Pengeluaran</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold text-danger">Daftar Kas Keluar</div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th class="text-end">Nominal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res_out = mysqli_query($koneksi, "SELECT * FROM kas_pengeluaran ORDER BY tanggal DESC, id DESC");
                                if (mysqli_num_rows($res_out) > 0):
                                    while ($row = mysqli_fetch_assoc($res_out)): ?>
                                        <tr>
                                            <td>
                                                <?= date('d/m/Y', strtotime($row['tanggal'])); ?>
                                            </td>
                                            <td>
                                                <?= $row['keterangan']; ?>
                                            </td>
                                            <td class="text-end text-danger fw-bold">Rp
                                                <?= number_format($row['nominal'], 0, ',', '.'); ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="admin_pengeluaran.php?token=<?= $kunci_rahasia; ?>&hapus_id=<?= $row['id']; ?>"
                                                    class="btn btn-link text-danger p-0 text-decoration-none small"
                                                    onclick="return confirm('Hapus catatan pengeluaran ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Belum ada data pengeluaran.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>