<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\JadwalMaster;
use App\Models\JimpitanHarian;
use App\Services\WetonService;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    /**
     * Show the WhatsApp reminder dashboard.
     */
    public function index()
    {
        $hari_ini = date('Y-m-d');
        WetonService::initJadwalHarian($hari_ini);

        $weton = WetonService::getHariPasaran($hari_ini);
        $hari = $weton['hari'];
        $pasaran = $weton['pasaran'];

        // Fetch citizens on duty today for display
        $dutyList = Warga::whereHas('jadwalMasters', function ($query) use ($hari, $pasaran) {
            $query->where('hari', $hari)->where('pasaran', $pasaran);
        })
        ->where('status_aktif', 1)
        ->get()
        ->map(function ($warga) use ($hari_ini) {
            $jh = JimpitanHarian::where('tanggal', $hari_ini)
                ->where('warga_id', $warga->id)
                ->first();

            $warga->status_tugas = $jh && $jh->status === 'Sudah Dikerjakan' ? 'Sudah Selesai' : 'Belum Selesai';
            $warga->nominal_jimpitan = $jh ? $jh->nominal : 0;
            return $warga;
        });

        return view('admin.pengingat', compact('hari', 'pasaran', 'dutyList'));
    }

    /**
     * Manual warning screen: displays standard click-to-chat links.
     */
    public function kirimManual()
    {
        $hari_ini = date('Y-m-d');
        $weton = WetonService::getHariPasaran($hari_ini);
        $hari = $weton['hari'];
        $pasaran = $weton['pasaran'];

        $dutyList = Warga::whereHas('jadwalMasters', function ($query) use ($hari, $pasaran) {
            $query->where('hari', $hari)->where('pasaran', $pasaran);
        })
        ->where('status_aktif', 1)
        ->get();

        $chats = [];
        foreach ($dutyList as $warga) {
            $nomorWa = preg_replace('/[^0-9]/', '', $warga->no_wa);
            if (str_starts_with($nomorWa, '0')) {
                $nomorWa = '62' . substr($nomorWa, 1);
            }

            $pesan = "Assalamualaikum Wr. Wb.\n\nMengingatkan kepada Bapak *$warga->nama*,\nBerdasarkan jadwal RT 35, malam ini (*$hari $pasaran*) adalah jadwal Anda untuk bertugas mengambil jimpitan warga.\n\nMohon kerjasamanya demi keamanan lingkungan kita.\nTerima kasih.\n\n— *Pengurus RT*";
            
            $chats[] = [
                'nama' => $warga->nama,
                'no_wa' => $warga->no_wa,
                'link' => "https://wa.me/" . $nomorWa . "?text=" . urlencode($pesan),
            ];
        }

        return view('admin.pengingat_manual', compact('hari', 'pasaran', 'chats'));
    }

    /**
     * Trigger automatic messages via the local Node.js Gateway.
     */
    public function kirimGateway()
    {
        $hari_ini = date('Y-m-d');
        $weton = WetonService::getHariPasaran($hari_ini);
        $hari = $weton['hari'];
        $pasaran = $weton['pasaran'];

        $dutyList = Warga::whereHas('jadwalMasters', function ($query) use ($hari, $pasaran) {
            $query->where('hari', $hari)->where('pasaran', $pasaran);
        })
        ->where('status_aktif', 1)
        ->get();

        if ($dutyList->isEmpty()) {
            return "Tidak ada jadwal jimpitan untuk hari ini ($hari $pasaran).";
        }

        $gatewayUrl = env('WA_GATEWAY_URL', 'http://127.0.0.1:3000/send');
        $logs = [];

        foreach ($dutyList as $warga) {
            if (empty($warga->no_wa)) {
                $logs[] = "Gagal mengirim ke $warga->nama: Nomor WhatsApp tidak terdaftar di database.";
                continue;
            }

            $pesan = "Assalamualaikum Wr. Wb.\n\nMengingatkan kepada Bapak *$warga->nama*,\nBerdasarkan jadwal RT 35, malam ini (*$hari $pasaran*) adalah jadwal Anda untuk bertugas mengambil jimpitan warga.\n\nMohon kerjasamanya demi keamanan lingkungan kita.\nTerima kasih.\n\n— *Pengurus RT*";

            try {
                $response = Http::timeout(10)->post($gatewayUrl, [
                    'to' => $warga->no_wa,
                    'message' => $pesan,
                ]);

                if ($response->successful() && isset($response->json()['status']) && $response->json()['status'] === 'success') {
                    $logs[] = "Peringatan terkirim ke $warga->nama ($warga->no_wa) via Gateway.";
                } else {
                    $logs[] = "Gagal mengirim ke $warga->nama ($warga->no_wa): Gateway merespon dengan status error.";
                }
            } catch (\Exception $e) {
                $logs[] = "Gagal mengirim ke $warga->nama ($warga->no_wa): Koneksi ke Gateway ($gatewayUrl) gagal. Error: " . $e->getMessage();
            }
        }

        return view('admin.pengingat_gateway_result', compact('logs'));
    }

    /**
     * Automated Cron Dispatcher using Fonnte API.
     */
    public function cronJimpitan()
    {
        // Set higher execution limit for slow loops
        set_time_limit(120);

        $hari_ini = date('Y-m-d');
        $weton = WetonService::getHariPasaran($hari_ini);
        $hari = $weton['hari'];
        $pasaran = $weton['pasaran'];

        $dutyList = Warga::whereHas('jadwalMasters', function ($query) use ($hari, $pasaran) {
            $query->where('hari', $hari)->where('pasaran', $pasaran);
        })
        ->where('status_aktif', 1)
        ->get();

        if ($dutyList->isEmpty()) {
            return "<strong>Laporan:</strong> Tidak ada warga yang bertugas pada hari <strong>$hari $pasaran</strong>.";
        }

        $tokenFonnte = "545CLb2zp4hcXhxjUVqJ";
        $salamVariasi = [
            "Assalamualaikum Wr. Wb.",
            "Sampurasun / Selamat sore Bapak/Ibu.",
            "Assalamualaikum, sugeng sore.",
            "Selamat sore Bapak/Ibu warga RT."
        ];

        $results = [];

        foreach ($dutyList as $warga) {
            if (empty($warga->no_wa)) {
                $results[] = "Lewati $warga->nama: Nomor WhatsApp tidak terdaftar di database.";
                continue;
            }

            $nomorWa = preg_replace('/[^0-9]/', '', $warga->no_wa);
            if (str_starts_with($nomorWa, '0')) {
                $nomorWa = '62' . substr($nomorWa, 1);
            }

            $salamPilihan = $salamVariasi[array_rand($salamVariasi)];
            $idUnik = substr(md5(time() . $warga->nama), 0, 5);
            $pesan = "$salamPilihan\n\nMengingatkan kepada Bapak/Ibu *$warga->nama*,\nBerdasarkan jadwal RT, malam ini (*$hari $pasaran*) adalah jadwal Anda untuk bertugas mengambil jimpitan warga.\n\nMohon kerjasamanya demi keamanan lingkungan kita.\nTerima kasih.\n\n— *Pengurus RT*\n_(Ref_ID: #$idUnik)_";

            try {
                $response = Http::withHeaders([
                    'Authorization' => $tokenFonnte,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $nomorWa,
                    'message' => $pesan,
                    'countryCode' => '62',
                ]);

                $results[] = "Pesan dikirim ke $warga->nama ($nomorWa).";
            } catch (\Exception $e) {
                $results[] = "Gagal kirim ke $warga->nama ($nomorWa): " . $e->getMessage();
            }

            // Anti-spam pause (only if there are more items to send)
            if ($warga !== $dutyList->last()) {
                $jedaAcak = rand(15, 30);
                sleep($jedaAcak);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Selesai memproses pengingat otomatis (Fonnte).',
            'logs' => $results
        ]);
    }
}
