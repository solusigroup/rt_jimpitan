<?php
include 'koneksi.php';
include 'fungsi_pasaran.php';

// Ambil tanggal hari ini (Format: YYYY-MM-DD)
$hari_ini = date('Y-m-d');
$weton    = getHariPasaran($hari_ini);

$hari_jawa    = $weton['hari'];
$pasaran_jawa = $weton['pasaran'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Jimpitan RT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include 'menu.php'; ?>
<div class="container">
    <div class="card shadow-sm text-center mb-4">
        <div class="card-body bg-primary text-white rounded">
            <h3>Jadwal Jimpitan Malam Ini</h3>
            <h5 class="fw-bold"><?= date('d F Y'); ?></h5>
            <span class="badge bg-warning text-dark fs-5"><?= $hari_jawa . ' ' . $pasaran_jawa; ?></span>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">Warga yang Bertugas Mengambil Jimpitan:</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Warga</th>
                        <th>No. Rumah</th>
                        <th>No. WhatsApp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query untuk mengambil warga berdasarkan hari pasaran saat ini
                    $query = "SELECT w.nama, w.no_rumah, w.no_wa 
                              FROM jadwal_master j
                              JOIN warga w ON j.warga_id = w.id
                              WHERE j.hari = '$hari_jawa' AND j.pasaran = '$pasaran_jawa' AND w.status_aktif = 1";
                    
                    $result = mysqli_query($koneksi, $query);
                    $no = 1;
                    
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>{$row['nama']}</td>
                                    <td>No. {$row['no_rumah']}</td>
                                    <td>{$row['no_wa']}</td>
                                  </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center text-muted'>Tidak ada jadwal bertugas malam ini.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
