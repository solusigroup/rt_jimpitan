<?php
include 'koneksi.php';

header('Content-Type: application/json');

// Hanya izinkan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak diizinkan.']);
    exit;
}

// Ambil input JSON
$inputRaw = file_get_contents('php://input');
$data = json_decode($inputRaw, true);

// Jika bukan JSON, fallback ke form POST biasa
if (!$data) {
    $data = $_POST;
}

$warga_id = isset($data['warga_id']) ? intval($data['warga_id']) : 0;
$tanggal  = isset($data['tanggal']) ? mysqli_real_escape_string($koneksi, $data['tanggal']) : '';
$status   = isset($data['status']) ? mysqli_real_escape_string($koneksi, $data['status']) : '';
$nominal  = isset($data['nominal']) ? intval($data['nominal']) : 0;

if ($warga_id <= 0 || empty($tanggal) || empty($status)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Parameter tidak valid.']);
    exit;
}

// Validasi nilai status
if (!in_array($status, ['Belum Dikerjakan', 'Sudah Dikerjakan'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Status tidak valid.']);
    exit;
}

// Jika status diubah menjadi Belum Dikerjakan, set nominal kembali ke 0
if ($status === 'Belum Dikerjakan') {
    $nominal = 0;
}

// Insert atau update status jimpitan harian beserta nominalnya
$query = "INSERT INTO jimpitan_harian (tanggal, warga_id, status, nominal)
          VALUES ('$tanggal', $warga_id, '$status', $nominal)
          ON DUPLICATE KEY UPDATE status = '$status', nominal = $nominal";

if (mysqli_query($koneksi, $query)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Status jimpitan berhasil diperbarui!',
        'warga_id' => $warga_id,
        'tanggal' => $tanggal,
        'new_status' => $status
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal memperbarui status ke database: ' . mysqli_error($koneksi)
    ]);
}
?>
