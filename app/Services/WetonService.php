<?php

namespace App\Services;

use App\Models\Warga;
use App\Models\JadwalMaster;
use App\Models\JimpitanHarian;

class WetonService
{
    /**
     * Calculate Javanese day and pasaran for a given date.
     * Reference point: 1970-01-01 is Kamis Wage.
     */
    public static function getHariPasaran(string $tanggalInput): array
    {
        $daftarHari    = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        $daftarPasaran = ["Legi", "Pahing", "Pon", "Wage", "Kliwon"];
        
        $tanggalPatokan = strtotime("1970-01-01");
        $tanggalTarget  = strtotime($tanggalInput);
        
        $selisihDetik = $tanggalTarget - $tanggalPatokan;
        $selisihHari  = floor($selisihDetik / (60 * 60 * 24));
        
        // Kamis = index 4, Wage = index 3
        $indeksHari    = ($selisihHari + 4) % 7;
        $indeksPasaran = ($selisihHari + 3) % 5;
        
        // Anticipate negative values (dates before 1970)
        if ($indeksHari < 0) $indeksHari += 7;
        if ($indeksPasaran < 0) $indeksPasaran += 5;
        
        return [
            'hari'    => $daftarHari[$indeksHari],
            'pasaran' => $daftarPasaran[$indeksPasaran]
        ];
    }

    /**
     * Initialize daily jimpitan harian records for the given date.
     */
    public static function initJadwalHarian(string $tanggal): void
    {
        $weton = self::getHariPasaran($tanggal);
        $hariJawa = $weton['hari'];
        $pasaranJawa = $weton['pasaran'];
        
        // Find active citizens on duty for today
        $wargaIds = JadwalMaster::where('hari', $hariJawa)
            ->where('pasaran', $pasaranJawa)
            ->whereHas('warga', function ($query) {
                $query->where('status_aktif', 1);
            })
            ->pluck('warga_id');

        foreach ($wargaIds as $wargaId) {
            // Check if record already exists to avoid duplicates (similar to INSERT IGNORE)
            JimpitanHarian::firstOrCreate(
                [
                    'tanggal' => $tanggal,
                    'warga_id' => $wargaId,
                ],
                [
                    'status' => 'Belum Dikerjakan',
                    'nominal' => 0,
                ]
            );
        }
    }
}
