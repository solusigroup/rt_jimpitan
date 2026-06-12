<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\JimpitanHarian;
use App\Models\KasSetting;
use App\Models\KasPengeluaran;
use App\Services\WetonService;

class LaporanController extends Controller
{
    /**
     * Show daily jimpitan logs and statistics.
     */
    public function harian()
    {
        $hari_ini = date('Y-m-d');
        WetonService::initJadwalHarian($hari_ini);

        $totalTugas = JimpitanHarian::count();
        $selesai = JimpitanHarian::where('status', 'Sudah Dikerjakan')->count();
        $belumSelesai = JimpitanHarian::where('status', 'Belum Dikerjakan')->count();
        $complianceRate = $totalTugas > 0 ? round(($selesai / $totalTugas) * 100) : 0;

        $logs = JimpitanHarian::with('warga')
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_update', 'desc')
            ->get()
            ->map(function ($log) {
                // Calculate weton name for each report date
                $weton = WetonService::getHariPasaran($log->tanggal);
                $log->hari_jawa = $weton['hari'];
                $log->pasaran_jawa = $weton['pasaran'];
                return $log;
            });

        return view('laporan.harian', compact('totalTugas', 'selesai', 'belumSelesai', 'complianceRate', 'logs'));
    }

    /**
     * Show financial cash flow report.
     */
    public function keuangan()
    {
        $setting = KasSetting::firstOrCreate(['id' => 1], ['saldo_awal' => 0]);
        $saldoAwal = $setting->saldo_awal;

        $totalPemasukan = JimpitanHarian::where('status', 'Sudah Dikerjakan')->sum('nominal');
        $totalPengeluaran = KasPengeluaran::sum('nominal');
        $saldoAkhir = $saldoAwal + $totalPemasukan - $totalPengeluaran;

        // Fetch recent 10 income logs
        $incomeLogs = JimpitanHarian::where('status', 'Sudah Dikerjakan')
            ->with('warga')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($log) {
                $weton = WetonService::getHariPasaran($log->tanggal);
                $log->weton_nama = $weton['hari'] . ' ' . $weton['pasaran'];
                return $log;
            });

        // Fetch recent 10 expense logs
        $expenseLogs = KasPengeluaran::orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('laporan.keuangan', compact('saldoAwal', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir', 'incomeLogs', 'expenseLogs'));
    }
}
