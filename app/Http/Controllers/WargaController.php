<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;
use Illuminate\Support\Facades\File;

class WargaController extends Controller
{
    /**
     * Display a listing of the resource and the create/edit form.
     */
    public function index(Request $request)
    {
        $wargaList = Warga::orderBy('id', 'desc')->get();
        $totalWarga = Warga::count();
        
        $editMode = false;
        $wargaEdit = null;

        if ($request->has('edit')) {
            $wargaEdit = Warga::find($request->edit);
            if ($wargaEdit) {
                $editMode = true;
            }
        }

        return view('admin.warga', compact('wargaList', 'totalWarga', 'editMode', 'wargaEdit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_rumah' => 'required|string|max:50',
            'no_wa' => 'nullable|string|max:50',
            'foto' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fotoName = 'warga_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
            
            // Ensure uploads directory exists
            $uploadPath = public_path('uploads');
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true, true);
            }
            
            $file->move($uploadPath, $fotoName);
        }

        Warga::create([
            'nama' => $request->nama,
            'no_rumah' => $request->no_rumah,
            'no_wa' => $request->no_wa ?? '',
            'foto' => $fotoName,
            'status_aktif' => 1,
        ]);

        return redirect()->route('admin.warga')->with('success', 'Data warga berhasil disimpan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $warga = Warga::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'no_rumah' => 'required|string|max:50',
            'no_wa' => 'nullable|string|max:50',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoName = $warga->foto;

        // Check if admin chose to delete the current photo
        if ($request->has('hapus_foto_aktif') && $request->hapus_foto_aktif == '1') {
            if ($fotoName && File::exists(public_path('uploads/' . $fotoName))) {
                File::delete(public_path('uploads/' . $fotoName));
            }
            $fotoName = null;
        }

        // Handle new file upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $newFotoName = 'warga_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
            
            $uploadPath = public_path('uploads');
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true, true);
            }
            
            if ($file->move($uploadPath, $newFotoName)) {
                // Delete old photo if it exists
                if ($fotoName && File::exists(public_path('uploads/' . $fotoName))) {
                    File::delete(public_path('uploads/' . $fotoName));
                }
                $fotoName = $newFotoName;
            }
        }

        $warga->update([
            'nama' => $request->nama,
            'no_rumah' => $request->no_rumah,
            'no_wa' => $request->no_wa ?? '',
            'foto' => $fotoName,
        ]);

        return redirect()->route('admin.warga')->with('success', 'Data warga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $warga = Warga::findOrFail($id);

        // Delete photo from disk if exists
        if ($warga->foto && File::exists(public_path('uploads/' . $warga->foto))) {
            File::delete(public_path('uploads/' . $warga->foto));
        }

        $warga->delete();

        return redirect()->route('admin.warga')->with('success', 'Data warga berhasil dihapus.');
    }
}
