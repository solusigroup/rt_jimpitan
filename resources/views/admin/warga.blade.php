@extends('layouts.app')

@section('title', 'Kelola Data Warga')

@section('content')
<div class="container mt-2 mb-5">
    
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Form Tambah / Edit -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header {{ $editMode ? 'bg-warning text-dark' : 'bg-primary text-white' }} py-3 fw-bold">
                    <i class="bi {{ $editMode ? 'bi-pencil-square' : 'bi-person-plus-fill' }} me-2"></i>
                    {{ $editMode ? 'Edit Data Warga' : 'Tambah Warga Baru' }}
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ $editMode ? route('admin.warga.update', $wargaEdit->id) : route('admin.warga.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $editMode ? $wargaEdit->nama : '') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">No. Rumah</label>
                            <input type="text" name="no_rumah" class="form-control" value="{{ old('no_rumah', $editMode ? $wargaEdit->no_rumah : '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">No. WhatsApp (Gunakan Kode Negara: 6281xxx)</label>
                            <input type="text" name="no_wa" class="form-control" placeholder="Contoh: 62812345678" value="{{ old('no_wa', $editMode ? $wargaEdit->no_wa : '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Foto Warga (Maksimal 2MB)</label>
                            @if ($editMode && !empty($wargaEdit->foto) && File::exists(public_path('uploads/' . $wargaEdit->foto)))
                                <div class="mb-2 d-flex align-items-center gap-3 bg-light p-2 rounded border">
                                    <img src="{{ asset('uploads/' . $wargaEdit->foto) }}" class="img-thumbnail rounded-circle object-fit-cover" style="width: 60px; height: 60px;" alt="Foto Warga">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="hapus_foto_aktif" value="1" id="hapusFoto">
                                        <label class="form-check-label small text-danger fw-semibold" for="hapusFoto">
                                            Hapus foto saat ini
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                        
                        @if ($editMode)
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning w-100 fw-bold text-dark">Simpan Perubahan</button>
                                <a href="{{ route('admin.warga') }}" class="btn btn-secondary w-50 fw-semibold">Batal</a>
                            </div>
                        @else
                            <button type="submit" class="btn btn-success w-100 fw-bold">Simpan Warga</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Warga -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3 fw-bold text-dark d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-table me-2 text-primary"></i>Daftar Registrasi Warga</span>
                    <span class="badge bg-secondary rounded-pill">Total: {{ $totalWarga }} Warga</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">Nama Warga</th>
                                    <th class="py-3">No. Rumah</th>
                                    <th class="py-3">No. WhatsApp</th>
                                    <th class="px-4 py-3 text-end" style="width: 200px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($wargaList as $row)
                                    @php
                                        // Initials for avatar
                                        $nameParts = explode(' ', $row->nama);
                                        if (count($nameParts) >= 2) {
                                            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                        } else {
                                            $initials = strtoupper(substr($row->nama, 0, 2));
                                        }
                                    @endphp
                                    <tr>
                                        <td class="px-4 fw-semibold text-dark">
                                            <div class="d-flex align-items-center gap-3">
                                                @if (!empty($row->foto) && File::exists(public_path('uploads/' . $row->foto)))
                                                    <img src="{{ asset('uploads/' . $row->foto) }}" class="rounded-circle object-fit-cover border shadow-sm" style="width: 40px; height: 40px; min-width: 40px;" alt="Foto">
                                                @else
                                                    <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary border fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px; font-size: 0.85rem;">
                                                        {{ $initials }}
                                                    </div>
                                                @endif
                                                <span>{{ $row->nama }}</span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-dark border px-2.5 py-1.5">{{ $row->no_rumah }}</span></td>
                                        <td>
                                            @if (!empty($row->no_wa))
                                                <a href="https://wa.me/{{ $row->no_wa }}" target="_blank" class="text-decoration-none text-success fw-semibold">
                                                    <i class="bi bi-whatsapp me-1"></i>{{ $row->no_wa }}
                                                </a>
                                            @else
                                                <span class="text-muted italic small"><i class="bi bi-dash-circle me-1"></i>Belum terisi</span>
                                            @endif
                                        </td>
                                        <td class="px-4 text-end">
                                            <div class="btn-group gap-1.5">
                                                <a href="{{ route('admin.warga', ['edit' => $row->id]) }}" class="btn btn-warning btn-sm text-dark d-inline-flex align-items-center gap-1 fw-semibold py-1">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                                <a href="{{ route('admin.warga.delete', $row->id) }}" class="btn btn-danger btn-sm d-inline-flex align-items-center gap-1 fw-semibold py-1" onclick="return confirm('Apakah Anda yakin ingin menghapus data warga {{ $row->nama }}?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="bi bi-people-fill fs-1 d-block mb-2 text-black-50"></i>
                                            Belum ada data warga terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
