<?php
include 'auth.php';
check_admin();
include 'koneksi.php';

// Inisialisasi variabel edit
$edit_mode = false;
$id_edit = '';
$nama_edit = '';
$no_rumah_edit = '';
$no_wa_edit = '';
$foto_edit = '';

// Ambil data untuk mode edit jika parameter 'edit' ada
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id_edit = intval($_GET['edit']);
    $res_edit = mysqli_query($koneksi, "SELECT * FROM warga WHERE id = $id_edit");
    if ($row_edit = mysqli_fetch_assoc($res_edit)) {
        $nama_edit = $row_edit['nama'];
        $no_rumah_edit = $row_edit['no_rumah'];
        $no_wa_edit = $row_edit['no_wa'];
        $foto_edit = $row_edit['foto'];
    }
}

// Proses Simpan Data (Create)
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $no_rumah = mysqli_real_escape_string($koneksi, $_POST['no_rumah']);
    $no_wa = mysqli_real_escape_string($koneksi, $_POST['no_wa']);
    
    // Proses Upload Foto
    $foto_name = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_original_name = $_FILES['foto']['name'];
        $file_size = $_FILES['foto']['size'];
        $file_ext = strtolower(pathinfo($file_original_name, PATHINFO_EXTENSION));
        
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($file_ext, $allowed_extensions)) {
            if ($file_size <= 2 * 1024 * 1024) { // Batas 2MB
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                $new_file_name = 'warga_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                $dest_path = 'uploads/' . $new_file_name;
                if (move_uploaded_file($file_tmp, $dest_path)) {
                    $foto_name = $new_file_name;
                }
            }
        }
    }
    
    mysqli_query($koneksi, "INSERT INTO warga (nama, no_rumah, no_wa, foto) VALUES ('$nama', '$no_rumah', '$no_wa', '$foto_name')");
    header("Location: admin_warga.php");
    exit;
}

// Proses Update Data (Update)
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $no_rumah = mysqli_real_escape_string($koneksi, $_POST['no_rumah']);
    $no_wa = mysqli_real_escape_string($koneksi, $_POST['no_wa']);
    
    // Ambil info foto warga saat ini
    $res_current = mysqli_query($koneksi, "SELECT foto FROM warga WHERE id = $id");
    $row_current = mysqli_fetch_assoc($res_current);
    $foto_name = $row_current['foto'];
    
    // Jika admin mencentang hapus foto aktif
    if (isset($_POST['hapus_foto_aktif']) && $_POST['hapus_foto_aktif'] == '1') {
        if (!empty($foto_name) && file_exists('uploads/' . $foto_name)) {
            unlink('uploads/' . $foto_name);
        }
        $foto_name = '';
    }
    
    // Proses Upload Foto Baru (jika ada)
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_original_name = $_FILES['foto']['name'];
        $file_size = $_FILES['foto']['size'];
        $file_ext = strtolower(pathinfo($file_original_name, PATHINFO_EXTENSION));
        
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($file_ext, $allowed_extensions)) {
            if ($file_size <= 2 * 1024 * 1024) { // Batas 2MB
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                $new_file_name = 'warga_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                $dest_path = 'uploads/' . $new_file_name;
                if (move_uploaded_file($file_tmp, $dest_path)) {
                    // Hapus foto lama dari disk jika ada
                    if (!empty($foto_name) && file_exists('uploads/' . $foto_name)) {
                        unlink('uploads/' . $foto_name);
                    }
                    $foto_name = $new_file_name;
                }
            }
        }
    }
    
    mysqli_query($koneksi, "UPDATE warga SET nama='$nama', no_rumah='$no_rumah', no_wa='$no_wa', foto='$foto_name' WHERE id=$id");
    header("Location: admin_warga.php");
    exit;
}

// Proses Hapus Data (Delete)
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    
    // Ambil info foto warga untuk dihapus dari disk
    $res_current = mysqli_query($koneksi, "SELECT foto FROM warga WHERE id = $id");
    if ($row_current = mysqli_fetch_assoc($res_current)) {
        $foto_name = $row_current['foto'];
        if (!empty($foto_name) && file_exists('uploads/' . $foto_name)) {
            unlink('uploads/' . $foto_name);
        }
    }
    
    mysqli_query($koneksi, "DELETE FROM warga WHERE id=$id");
    header("Location: admin_warga.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Warga - Jimpitan RT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<?php include 'menu.php'; ?>

<div class="container mt-2 mb-5">
    <div class="row g-4">
        <!-- Form Tambah / Edit (Create & Update) -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header <?= $edit_mode ? 'bg-warning text-dark' : 'bg-primary text-white' ?> py-3 fw-bold">
                    <i class="bi <?= $edit_mode ? 'bi-pencil-square' : 'bi-person-plus-fill' ?> me-2"></i>
                    <?= $edit_mode ? 'Edit Data Warga' : 'Tambah Warga Baru' ?>
                </div>
                <div class="card-body p-4">
                    <form method="POST" enctype="multipart/form-data">
                        <?php if ($edit_mode): ?>
                            <input type="hidden" name="id" value="<?= $id_edit ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama_edit) ?>" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">No. Rumah</label>
                            <input type="text" name="no_rumah" class="form-control" value="<?= htmlspecialchars($no_rumah_edit) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">No. WhatsApp (Gunakan Kode Negara: 6281xxx)</label>
                            <input type="text" name="no_wa" class="form-control" placeholder="Contoh: 62812345678" value="<?= htmlspecialchars($no_wa_edit) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Foto Warga (Maksimal 2MB)</label>
                            <?php if ($edit_mode && !empty($foto_edit) && file_exists('uploads/' . $foto_edit)): ?>
                                <div class="mb-2 d-flex align-items-center gap-3 bg-light p-2 rounded border">
                                    <img src="uploads/<?= $foto_edit ?>" class="img-thumbnail rounded-circle object-fit-cover" style="width: 60px; height: 60px;" alt="Foto Warga">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="hapus_foto_aktif" value="1" id="hapusFoto">
                                        <label class="form-check-label small text-danger fw-semibold" for="hapusFoto">
                                            Hapus foto saat ini
                                        </label>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                        
                        <?php if ($edit_mode): ?>
                            <div class="d-flex gap-2">
                                <button type="submit" name="update" class="btn btn-warning w-100 fw-bold text-dark">Simpan Perubahan</button>
                                <a href="admin_warga.php" class="btn btn-secondary w-50 fw-semibold">Batal</a>
                            </div>
                        <?php else: ?>
                            <button type="submit" name="tambah" class="btn btn-success w-100 fw-bold">Simpan Warga</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Warga (Read) -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3 fw-bold text-dark d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-table me-2 text-primary"></i>Daftar Registrasi Warga</span>
                    <span class="badge bg-secondary rounded-pill">Total: <?php
                        $count_res = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM warga");
                        $count_row = mysqli_fetch_assoc($count_res);
                        echo $count_row['total'];
                    ?> Warga</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">Nama Warga</th>
                                    <th class="py-3">No. Rumah</th>
                                    <th class="py-3">No. WhatsApp</th>
                                    <th class="px-4 py-3 text-end" style="width: 200px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = mysqli_query($koneksi, "SELECT * FROM warga ORDER BY id DESC");
                                if (mysqli_num_rows($res) > 0):
                                    while($row = mysqli_fetch_assoc($res)): ?>
                                    <tr>
                                        <td class="px-4 fw-semibold text-dark">
                                            <div class="d-flex align-items-center gap-3">
                                                <?php if (!empty($row['foto']) && file_exists('uploads/' . $row['foto'])): ?>
                                                    <img src="uploads/<?= $row['foto'] ?>" class="rounded-circle object-fit-cover border shadow-sm" style="width: 40px; height: 40px; min-width: 40px;" alt="Foto">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary border fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px; font-size: 0.85rem; height: 40px; width: 40px;">
                                                        <?php
                                                        $name_parts = explode(' ', $row['nama']);
                                                        if (count($name_parts) >= 2) {
                                                            echo strtoupper(substr($name_parts[0], 0, 1) . substr($name_parts[1], 0, 1));
                                                        } else {
                                                            echo strtoupper(substr($row['nama'], 0, 2));
                                                        }
                                                        ?>
                                                    </div>
                                                <?php endif; ?>
                                                <span><?= htmlspecialchars($row['nama']) ?></span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-dark border px-2.5 py-1.5"><?= htmlspecialchars($row['no_rumah']) ?></span></td>
                                        <td>
                                            <?php if (!empty($row['no_wa'])): ?>
                                                <a href="https://wa.me/<?= $row['no_wa'] ?>" target="_blank" class="text-decoration-none text-success fw-semibold">
                                                    <i class="bi bi-whatsapp me-1"></i><?= htmlspecialchars($row['no_wa']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted italic small"><i class="bi bi-dash-circle me-1"></i>Belum terisi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 text-end">
                                            <div class="btn-group gap-1.5">
                                                <a href="?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm text-dark d-inline-flex align-items-center gap-1 fw-semibold py-1">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                                <a href="?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm d-inline-flex align-items-center gap-1 fw-semibold py-1" onclick="return confirm('Apakah Anda yakin ingin menghapus data warga <?= htmlspecialchars(addslashes($row['nama'])) ?>?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile;
                                else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="bi bi-people-fill fs-1 d-block mb-2 text-black-50"></i>
                                            Belum ada data warga terdaftar.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
