<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\JadwalMaster;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource and the create/edit form.
     */
    public function index(Request $request)
    {
        $wargaList = Warga::orderBy('nama', 'asc')->get();
        $totalSchedules = JadwalMaster::count();
        
        // Custom order to match Javanese days and market days
        $schedules = JadwalMaster::with('warga')
            ->get()
            ->sortBy(function ($schedule) {
                $daysOrder = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];
                $pasaranOrder = ['Legi' => 1, 'Pahing' => 2, 'Pon' => 3, 'Wage' => 4, 'Kliwon' => 5];
                
                $dayVal = $daysOrder[$schedule->hari] ?? 99;
                $pasaranVal = $pasaranOrder[$schedule->pasaran] ?? 99;
                
                return sprintf('%02d-%02d', $dayVal, $pasaranVal);
            });

        $editMode = false;
        $scheduleEdit = null;

        if ($request->has('edit')) {
            $scheduleEdit = JadwalMaster::find($request->edit);
            if ($scheduleEdit) {
                $editMode = true;
            }
        }

        return view('admin.jadwal', compact('wargaList', 'schedules', 'totalSchedules', 'editMode', 'scheduleEdit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'warga_id' => 'required|integer|exists:warga,id',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'pasaran' => 'required|string|in:Legi,Pahing,Pon,Wage,Kliwon',
        ]);

        JadwalMaster::create([
            'warga_id' => $request->warga_id,
            'hari' => $request->hari,
            'pasaran' => $request->pasaran,
        ]);

        return redirect()->route('admin.jadwal')->with('success', 'Plot jadwal baru berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $schedule = JadwalMaster::findOrFail($id);

        $request->validate([
            'warga_id' => 'required|integer|exists:warga,id',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'pasaran' => 'required|string|in:Legi,Pahing,Pon,Wage,Kliwon',
        ]);

        $schedule->update([
            'warga_id' => $request->warga_id,
            'hari' => $request->hari,
            'pasaran' => $request->pasaran,
        ]);

        return redirect()->route('admin.jadwal')->with('success', 'Plot jadwal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedule = JadwalMaster::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.jadwal')->with('success', 'Plot jadwal berhasil dihapus.');
    }
}
