<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasSetting;
use App\Models\KasPengeluaran;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of expenses and treasury setup.
     */
    public function index()
    {
        // Fetch or initialize the initial cash balance
        $setting = KasSetting::firstOrCreate(
            ['id' => 1],
            ['saldo_awal' => 0]
        );

        // Fetch all expenses sorted by date desc
        $expenses = KasPengeluaran::orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.pengeluaran', compact('setting', 'expenses'));
    }

    /**
     * Update the initial cash balance.
     */
    public function updateSaldoAwal(Request $request)
    {
        $request->validate([
            'saldo_awal' => 'required|integer|min:0',
        ]);

        KasSetting::updateOrCreate(
            ['id' => 1],
            ['saldo_awal' => $request->saldo_awal]
        );

        return redirect()->route('admin.pengeluaran')->with('success', 'Saldo awal kas berhasil diperbarui.');
    }

    /**
     * Store a new treasury expense.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|integer|min:0',
        ]);

        KasPengeluaran::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'nominal' => $request->nominal,
        ]);

        return redirect()->route('admin.pengeluaran')->with('success', 'Catatan pengeluaran baru berhasil ditambahkan.');
    }

    /**
     * Remove the treasury expense from storage.
     */
    public function destroy($id)
    {
        $expense = KasPengeluaran::findOrFail($id);
        $expense->delete();

        return redirect()->route('admin.pengeluaran')->with('success', 'Catatan pengeluaran berhasil dihapus.');
    }
}
