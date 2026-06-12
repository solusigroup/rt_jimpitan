<?php
// Mengatur batas waktu eksekusi agar tidak timeout jika warga yang bertugas agak banyak
set_time_limit(120);

include 'koneksi.php';
include 'fungsi_pasaran.php';

// Ambil tanggal hari ini dan cari Weton-nya (Hari + Pasaran)
$hari_ini = date('Y-m-d');
$weton = getHariPasaran($hari_ini);
$hari = $weton['hari'];
$pasaran = $weton['pasaran'];

// 1. Ambil data warga yang terjadwal bertugas malam ini dari MariaDB
$query = "SELECT w.nama, w.no_wa 
          FROM jadwal_master j
          JOIN warga w ON j.warga_id = w.id
          WHERE j.hari = '$hari' AND j.pasaran = '$pasaran' AND w.status_aktif = 1";

$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    $token_fonnte = "545CLb2zp4hcXhxjUVqJ";

    // Daftar variasi salam pembuka agar pesan tidak seragam
    $salam_variasi = [
        "Assalamualaikum Wr. Wb.",
        "Sampurasun / Selamat sore Bapak/Ibu.",
        "Assalamualaikum, sugeng sore.",
        "Selamat sore Bapak/Ibu warga RT."
    ];

    while ($warga = mysqli_fetch_assoc($result)) {
        $nama_warga = $warga['nama'];
        $nomor_wa = preg_replace('/[^0-9]/', '', $warga['no_wa']);

        if (substr($nomor_wa, 0, 1) === '0') {
            $nomor_wa = '62' . substr($nomor_wa, 1);
        }

        // Ambil salam secara acak
        $salam_pilihan = $salam_variasi[array_rand($salam_variasi)];

        // Buat string acak pendek (id unik) untuk disisipkan di bawah pesan
        $id_unik = substr(md5(time() . $nama_warga), 0, 5);

        // TEMPLATE PESAN BARU DENGAN VARIASI ANTI-SPAM
        $pesan = "$salam_pilihan\n\nMengingatkan kepada Bapak/Ibu *$nama_warga*,\nBerdasarkan jadwal RT, malam ini (*$hari $pasaran*) adalah jadwal Anda untuk bertugas mengambil jimpitan warga.\n\nMohon kerjasamanya demi keamanan lingkungan kita.\nTeria kasih.\n\n— *Pengurus RT*\n_(Ref_ID: #$id_unik)_";

        // Proses cURL Fonnte
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => array(
                'target' => $nomor_wa,
                'message' => $pesan,
                'countryCode' => '62',
            ),
            CURLOPT_HTTPHEADER => array("Authorization: $token_fonnte"),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        echo "Pesan dikirim ke $nama_warga... ";

        // TIPS ANTI-SPAM UTAMA: Beri jeda acak antara 15 hingga 30 detik sebelum lanjut ke warga berikutnya
        $jeda_acak = rand(15, 30);
        echo "Menunggu jeda aman: $jeda_acak detik.<br>";
        sleep($jeda_acak);
    }
    echo "<br><strong>Laporan:</strong> Selesai mengirim seluruh pengingat.";
} else {
    echo "<strong>Laporan:</strong> Tidak ada warga yang bertugas pada hari <strong>$hari $pasaran</strong>.";
}
?>