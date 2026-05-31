<?php
function getHariPasaran($tanggalInput) {
    $daftarHari    = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    $daftarPasaran = ["Legi", "Pahing", "Pon", "Wage", "Kliwon"];
    
    // Patokan: 1 Januari 1970 adalah Kamis Wage
    $tanggalPatokan = strtotime("1970-01-01");
    $tanggalTarget  = strtotime($tanggalInput);
    
    // Hitung selisih hari
    $selisihDetik = $tanggalTarget - $tanggalPatokan;
    $selisihHari  = floor($selisihDetik / (60 * 60 * 24));
    
    // Rumus sisa hasil bagi (modulus) ditambah indeks patokan
    // Kamis = indeks 4, Wage = indeks 3
    $indeksHari    = ($selisihHari + 4) % 7;
    $indeksPasaran = ($selisihHari + 3) % 5;
    
    // Antisipasi jika hasil minus (tanggal sebelum 1970)
    if ($indeksHari < 0) $indeksHari += 7;
    if ($indeksPasaran < 0) $indeksPasaran += 5;
    
    return [
        'hari'    => $daftarHari[$indeksHari],
        'pasaran' => $daftarPasaran[$indeksPasaran]
    ];
}
?>
