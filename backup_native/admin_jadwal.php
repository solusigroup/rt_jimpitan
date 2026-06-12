<?php
include 'auth.php';
check_admin();
include 'koneksi.php';

// Inisialisasi variabel edit
$edit_mode = false;
$id_edit = '';
$warga_id_edit = '';
$hari_edit = '';
$pasaran_edit = '';

// Ambil data untuk mode edit jika parameter 'edit' ada
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id_edit = intval($_GET['edit']);
    $res_edit = mysqli_query($koneksi, "SELECT * FROM jadwal_master WHERE id = $id_edit");
    if ($row_edit = mysqli_fetch_assoc($res_edit)) {
        $warga_id_edit = $row_edit['warga_id'];
        $hari_edit = $row_edit['hari'];
        $pasaran_edit = $row_edit['pasaran'];
    }
}

// Proses Simpan Jadwal (Create)
if (isset($_POST['set_jadwal'])) {
    $warga_id = intval($_POST['warga_id']);
    $hari = mysqli_real_escape_string($koneksi, $_POST['hari']);
    $pasaran = mysqli_real_escape_string($koneksi, $_POST['pasaran']);
    
    mysqli_query($koneksi, "INSERT INTO jadwal_master (warga_id, hari, pasaran) VALUES ($warga_id, '$hari', '$pasaran')");
    header("Location: admin_jadwal.php");
    exit;
}

// Proses Update Jadwal (Update)
if (isset($_POST['update_jadwal'])) {
    $id = intval($_POST['id']);
    $warga_id = intval($_POST['warga_id']);
    $hari = mysqli_real_escape_string($koneksi, $_POST['hari']);
    $pasaran = mysqli_real_escape_string($koneksi, $_POST['pasaran']);
    
    mysqli_query($koneksi, "UPDATE jadwal_master SET warga_id=$warga_id, hari='$hari', pasaran='$pasaran' WHERE id=$id");
    header("Location: admin_jadwal.php");
    exit;
}

// Proses Hapus Jadwal (Delete)
if (isset($_GET['hapus_jadwal'])) {
    $id = intval($_GET['hapus_jadwal']);
    mysqli_query($koneksi, "DELETE FROM jadwal_master WHERE id=$id");
    header("Location: admin_jadwal.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Jadwal - Jimpitan RT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<?php include 'menu.php'; ?>

<div class="container mt-2 mb-5">
    <!-- Form Plotting / Edit Jadwal (Create & Update) -->
    <div class="card shadow-sm border-0 mb-4 rounded-3">
        <div class="card-header <?= $edit_mode ? 'bg-warning text-dark' : 'bg-primary text-white' ?> py-3 fw-bold">
            <i class="bi <?= $edit_mode ? 'bi-pencil-square' : 'bi-calendar-plus-fill' ?> me-2"></i>
            <?= $edit_mode ? 'Edit Plot Jadwal Jimpitan' : 'Plotting Jadwal Jimpitan Baru' ?>
        </div>
        <div class="card-body p-4">
            <form method="POST" class="row g-3 align-items-end">
                <?php if ($edit_mode): ?>
                    <input type="hidden" name="id" value="<?= $id_edit ?>">
                <?php endif; ?>
                
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary small">Pilih Warga</label>
                    <select name="warga_id" class="form-select" required>
                        <option value="">-- Pilih Warga --</option>
                        <?php
                        $warga = mysqli_query($koneksi, "SELECT * FROM warga ORDER BY nama ASC");
                        while($w = mysqli_fetch_assoc($warga)) {
                            $selected = ($w['id'] == $warga_id_edit) ? 'selected' : '';
                            echo "<option value='{$w['id']}' $selected>{$w['nama']} (No. {$w['no_rumah']})</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary small">Hari</label>
                    <select name="hari" class="form-select" required>
                        <option value="">-- Pilih Hari --</option>
                        <?php
                        $hari_options = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                        foreach ($hari_options as $h) {
                            $selected = ($h == $hari_edit) ? 'selected' : '';
                            echo "<option value='$h' $selected>$h</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary small">Pasaran</label>
                    <select name="pasaran" class="form-select" required>
                        <option value="">-- Pilih Pasaran --</option>
                        <?php
                        $pasaran_options = ['Legi', 'Pahing', 'Pon', 'Wage', 'Kliwon'];
                        foreach ($pasaran_options as $p) {
                            $selected = ($p == $pasaran_edit) ? 'selected' : '';
                            echo "<option value='$p' $selected>$p</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <?php if ($edit_mode): ?>
                        <div class="d-flex gap-2">
                            <button type="submit" name="update_jadwal" class="btn btn-warning w-100 fw-bold text-dark">Update</button>
                            <a href="admin_jadwal.php" class="btn btn-secondary w-100 fw-semibold">Batal</a>
                        </div>
                    <?php else: ?>
                        <button type="submit" name="set_jadwal" class="btn btn-success w-100 fw-bold">Tambah Plot</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Jadwal Terpasang (Read & Delete) -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white py-3 fw-bold text-dark d-flex justify-content-between align-items-center">
            <span><i class="bi bi-calendar-check me-2 text-primary"></i>Daftar Distribusi Jadwal Aktif</span>
            <span class="badge bg-secondary rounded-pill">Total: <?php
                $count_res = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM jadwal_master");
                $count_row = mysqli_fetch_assoc($count_res);
                echo $count_row['total'];
            ?> Plot</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Warga Terplot</th>
                            <th class="py-3">No. Rumah</th>
                            <th class="py-3">Hari Pasaran Jawa</th>
                            <th class="px-4 py-3 text-end" style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT j.id, w.nama, w.no_rumah, j.hari, j.pasaran 
                                FROM jadwal_master j 
                                JOIN warga w ON j.warga_id = w.id 
                                ORDER BY FIELD(j.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'),
                                         FIELD(j.pasaran, 'Legi','Pahing','Pon','Wage','Kliwon')";
                        $res = mysqli_query($koneksi, $sql);
                        if (mysqli_num_rows($res) > 0):
                            while($row = mysqli_fetch_assoc($res)): ?>
                            <tr>
                                <td class="px-4 fw-semibold text-dark"><?= htmlspecialchars($row['nama']) ?></td>
                                <td><span class="badge bg-light text-dark border px-2.5 py-1.5"><?= htmlspecialchars($row['no_rumah']) ?></span></td>
                                <td>
                                    <span class="badge bg-primary text-white px-3 py-2 fw-semibold">
                                        <i class="bi bi-calendar-event me-1"></i><?= htmlspecialchars($row['hari']) ?> <?= htmlspecialchars($row['pasaran']) ?>
                                    </span>
                                </td>
                                <td class="px-4 text-end">
                                    <div class="btn-group gap-1.5">
                                        <a href="?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm text-dark d-inline-flex align-items-center gap-1 fw-semibold py-1">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="?hapus_jadwal=<?= $row['id'] ?>" class="btn btn-danger btn-sm d-inline-flex align-items-center gap-1 fw-semibold py-1" onclick="return confirm('Apakah Anda yakin ingin menghapus plot jadwal <?= htmlspecialchars(addslashes($row['hari'])) ?> <?= htmlspecialchars(addslashes($row['pasaran'])) ?> untuk <?= htmlspecialchars(addslashes($row['nama'])) ?>?')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2 text-black-50"></i>
                                    Belum ada plot jadwal jimpitan yang diatur.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
