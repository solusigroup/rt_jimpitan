<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';
include 'fungsi_pasaran.php';

// Cari weton untuk hari ini
$hari_ini = date('Y-m-d');
$weton = getHariPasaran($hari_ini);
$hari = $weton['hari'];
$pasaran = $weton['pasaran'];

// Ambil data warga yang bertugas nanti malam
$query = "SELECT w.nama, w.no_wa 
          FROM jadwal_master j
          JOIN warga w ON j.warga_id = w.id
          WHERE j.hari = '$hari' AND j.pasaran = '$pasaran' AND w.status_aktif = 1";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Gagal menjalankan query database: " . mysqli_error($koneksi));
}

if (mysqli_num_rows($result) > 0) {
    while ($warga = mysqli_fetch_assoc($result)) {
        $nama_warga = $warga['nama'];
        $nomor_wa = $warga['no_wa'];

        // Template Pesan Pengingat
        $pesan = "Assalamualaikum Wr. Wb.\n\nMengingatkan kepada Bapak *$nama_warga*,\nBerdasarkan jadwal RT 35, malam ini (*$hari $pasaran*) adalah jadwal Anda untuk bertugas mengambil jimpitan warga.\n\nMohon kerjasamanya demi keamanan lingkungan kita.\nTerima kasih.\n\n— *Pengurus RT*";

        // Tentukan URL Gateway Node.js (jika tidak didefinisikan di koneksi_custom.php, gunakan localhost:3000)
        $gateway_url = isset($wa_gateway_url) && !empty($wa_gateway_url) ? $wa_gateway_url : 'http://127.0.0.1:3000/send';

        // --- PROSES KIRIM WHATSAPP VIA GATEWAY NODE.JS (whatsapp-web.js) ---
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $gateway_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array(
                'to' => $nomor_wa,
                'message' => $pesan
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);
        curl_close($curl);

        // Log status di server
        if ($response === false) {
            echo "Gagal mengirim ke $nama_warga ($nomor_wa): Koneksi ke Gateway ($gateway_url) gagal. Error: $curl_error<br>";
        } else {
            $res_data = json_decode($response, true);
            if ($http_code === 200 && isset($res_data['status']) && $res_data['status'] === 'success') {
                echo "Peringatan terkirim ke $nama_warga ($nomor_wa)<br>";
            } else {
                $err_msg = isset($res_data['message']) ? $res_data['message'] : 'Response dari Gateway tidak valid.';
                echo "Gagal mengirim ke $nama_warga ($nomor_wa): Gateway merespon dengan error (HTTP $http_code): $err_msg<br>";
            }
        }
    }
} else {
    echo "Tidak ada jadwal jimpitan untuk hari ini ($hari $pasaran).";
}
?>