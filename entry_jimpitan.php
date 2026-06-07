<?php
include 'auth.php';
check_admin();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

$warga_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$tanggal = isset($_GET['tanggal']) ? mysqli_real_escape_string($koneksi, $_GET['tanggal']) : date('Y-m-d');

// Tarik data warga
$query_warga = "SELECT * FROM warga WHERE id = $warga_id";
$res_warga = mysqli_query($koneksi, $query_warga);
$warga = mysqli_fetch_assoc($res_warga);

if (!$warga) {
    die("Warga tidak ditemukan.");
}

// Proses Simpan Nominal Jimpitan
if (isset($_POST['simpan_jimpitan'])) {
    $nominal = preg_replace('/[^0-9]/', '', $_POST['nominal']);
    $nominal = intval($nominal);
    
    // Update atau insert status jimpitan harian
    $query_save = "INSERT INTO jimpitan_harian (tanggal, warga_id, status, nominal)
                   VALUES ('$tanggal', $warga_id, 'Sudah Dikerjakan', $nominal)
                   ON DUPLICATE KEY UPDATE status = 'Sudah Dikerjakan', nominal = $nominal";
    
    if (mysqli_query($koneksi, $query_save)) {
        header("Location: admin_pengingat.php?token=$kunci_rahasia");
        exit();
    } else {
        $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
}

// Tarik nominal jimpitan saat ini jika ada
$query_curr = "SELECT nominal FROM jimpitan_harian WHERE tanggal = '$tanggal' AND warga_id = $warga_id";
$res_curr = mysqli_query($koneksi, $query_curr);
$curr_data = mysqli_fetch_assoc($res_curr);
$current_nominal = isset($curr_data['nominal']) ? $curr_data['nominal'] : 1000; // Default nominal 1000
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Jimpitan Warga</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include 'menu.php'; ?>

    <div class="container mt-4" style="max-width: 500px;">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold">✏️ Input Uang Jimpitan</h5>
            </div>
            <div class="card-body p-4">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error; ?></div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="small text-secondary fw-bold">Nama Warga</label>
                    <div class="fs-5 fw-bold text-dark"><?= htmlspecialchars($warga['nama']); ?></div>
                    <small class="text-muted">Rumah No: <?= htmlspecialchars($warga['no_rumah']); ?></small>
                </div>

                <div class="mb-3">
                    <label class="small text-secondary fw-bold">Tanggal Piket</label>
                    <div class="fw-semibold text-dark"><?= date('d F Y', strtotime($tanggal)); ?></div>
                </div>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label small text-secondary fw-bold">Jumlah Jimpitan (Rp)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light fw-bold text-secondary">Rp</span>
                            <input type="number" name="nominal" class="form-control fw-bold text-primary" 
                                   value="<?= $current_nominal; ?>" required autofocus min="0">
                        </div>
                        <div class="form-text">Masukkan nominal uang jimpitan yang ditaruh (biasanya 500 atau 1000).</div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="admin_pengingat.php?token=<?= $kunci_rahasia; ?>" class="btn btn-outline-secondary w-50 fw-semibold py-2">
                            Batal
                        </a>
                        <button type="submit" name="simpan_jimpitan" class="btn btn-primary w-50 fw-bold py-2">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
