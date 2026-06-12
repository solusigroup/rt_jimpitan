<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\JadwalMaster;
use App\Models\JimpitanHarian;
use App\Services\WetonService;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Show today's jimpitan duties dashboard.
     */
    public function index()
    {
        $hari_ini = date('Y-m-d');
        
        // Auto-initialize daily jimpitan records for today if not already created
        WetonService::initJadwalHarian($hari_ini);
        
        // Calculate the weton (day and Javanese market day)
        $weton = WetonService::getHariPasaran($hari_ini);
        $hariJawa = $weton['hari'];
        $pasaranJawa = $weton['pasaran'];

        // Get citizens on duty for tonight
        $piketMalamIni = Warga::whereHas('jadwalMasters', function ($query) use ($hariJawa, $pasaranJawa) {
            $query->where('hari', $hariJawa)->where('pasaran', $pasaranJawa);
        })
        ->where('status_aktif', 1)
        ->get()
        ->map(function ($warga) use ($hari_ini) {
            // Retrieve today's specific jimpitan record for this citizen
            $jh = JimpitanHarian::where('tanggal', $hari_ini)
                ->where('warga_id', $warga->id)
                ->first();
            
            $warga->status = $jh ? $jh->status : 'Belum Dikerjakan';
            $warga->nominal = $jh ? $jh->nominal : 0;
            return $warga;
        });

        return view('home', compact('hari_ini', 'weton', 'piketMalamIni'));
    }

    /**
     * Show the full schedule grid.
     */
    public function jadwalLengkap()
    {
        $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $pasaran_list = ['Pahing', 'Pon', 'Wage', 'Kliwon', 'Legi'];

        // Initialize empty matrix
        $matrix = [];
        foreach ($hari_list as $h) {
            foreach ($pasaran_list as $p) {
                $matrix[$h][$p] = [];
            }
        }

        // Fetch all schedules with active warga
        $schedules = JadwalMaster::whereHas('warga', function ($q) {
            $q->where('status_aktif', 1);
        })->with('warga')->get();

        foreach ($schedules as $s) {
            $matrix[$s->hari][$s->pasaran][] = [
                'nama' => $s->warga->nama,
                'no_rumah' => $s->warga->no_rumah,
                'no_wa' => $s->warga->no_wa
            ];
        }

        return view('jadwal.index', compact('hari_list', 'pasaran_list', 'matrix'));
    }

    /**
     * AJAX endpoint to update the status/nominal of jimpitan.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'warga_id' => 'required|integer|exists:warga,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Belum Dikerjakan,Sudah Dikerjakan',
            'nominal' => 'nullable|integer|min:0'
        ]);

        try {
            $jimpitan = JimpitanHarian::updateOrCreate(
                [
                    'tanggal' => $request->tanggal,
                    'warga_id' => $request->warga_id,
                ],
                [
                    'status' => $request->status,
                    'nominal' => $request->status === 'Sudah Dikerjakan' ? ($request->nominal ?? 0) : 0,
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Status jimpitan berhasil diperbarui!',
                'data' => $jimpitan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
}
