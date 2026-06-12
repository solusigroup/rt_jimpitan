@extends('layouts.app')

@section('title', 'Atur Plotting Jadwal')

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

    <!-- Form Plotting / Edit Jadwal -->
    <div class="card shadow-sm border-0 mb-4 rounded-3">
        <div class="card-header {{ $editMode ? 'bg-warning text-dark' : 'bg-primary text-white' }} py-3 fw-bold">
            <i class="bi {{ $editMode ? 'bi-pencil-square' : 'bi-calendar-plus-fill' }} me-2"></i>
            {{ $editMode ? 'Edit Plot Jadwal Jimpitan' : 'Plotting Jadwal Jimpitan Baru' }}
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ $editMode ? route('admin.jadwal.update', $scheduleEdit->id) : route('admin.jadwal.store') }}" class="row g-3 align-items-end">
                @csrf
                
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary small">Pilih Warga</label>
                    <select name="warga_id" class="form-select" required>
                        <option value="">-- Pilih Warga --</option>
                        @foreach ($wargaList as $w)
                            @php
                                $selected = (old('warga_id', $editMode ? $scheduleEdit->warga_id : '') == $w->id) ? 'selected' : '';
                            @endphp
                            <option value="{{ $w->id }}" {{ $selected }}>{{ $w->nama }} (No. {{ $w->no_rumah }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary small">Hari</label>
                    <select name="hari" class="form-select" required>
                        <option value="">-- Pilih Hari --</option>
                        @php
                            $hari_options = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                        @endphp
                        @foreach ($hari_options as $h)
                            @php
                                $selected = (old('hari', $editMode ? $scheduleEdit->hari : '') == $h) ? 'selected' : '';
                            @endphp
                            <option value="{{ $h }}" {{ $selected }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary small">Pasaran</label>
                    <select name="pasaran" class="form-select" required>
                        <option value="">-- Pilih Pasaran --</option>
                        @php
                            $pasaran_options = ['Legi', 'Pahing', 'Pon', 'Wage', 'Kliwon'];
                        @endphp
                        @foreach ($pasaran_options as $p)
                            @php
                                $selected = (old('pasaran', $editMode ? $scheduleEdit->pasaran : '') == $p) ? 'selected' : '';
                            @endphp
                            <option value="{{ $p }}" {{ $selected }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    @if ($editMode)
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning w-100 fw-bold text-dark">Update</button>
                            <a href="{{ route('admin.jadwal') }}" class="btn btn-secondary w-100 fw-semibold">Batal</a>
                        </div>
                    @else
                        <button type="submit" class="btn btn-success w-100 fw-bold">Tambah Plot</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Jadwal Terpasang -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white py-3 fw-bold text-dark d-flex justify-content-between align-items-center">
            <span><i class="bi bi-calendar-check me-2 text-primary"></i>Daftar Distribusi Jadwal Aktif</span>
            <span class="badge bg-secondary rounded-pill">Total: {{ $totalSchedules }} Plot</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Warga Terplot</th>
                            <th class="py-3">No. Rumah</th>
                            <th class="py-3">Hari Pasaran Jawa</th>
                            <th class="px-4 py-3 text-end" style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schedules as $row)
                            <tr>
                                <td class="px-4 fw-semibold text-dark">{{ $row->warga->nama }}</td>
                                <td><span class="badge bg-light text-dark border px-2.5 py-1.5">{{ $row->warga->no_rumah }}</span></td>
                                <td>
                                    <span class="badge bg-primary text-white px-3 py-2 fw-semibold">
                                        <i class="bi bi-calendar-event me-1"></i>{{ $row->hari }} {{ $row->pasaran }}
                                    </span>
                                </td>
                                <td class="px-4 text-end">
                                    <div class="btn-group gap-1.5">
                                        <a href="{{ route('admin.jadwal', ['edit' => $row->id]) }}" class="btn btn-warning btn-sm text-dark d-inline-flex align-items-center gap-1 fw-semibold py-1">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="{{ route('admin.jadwal.delete', $row->id) }}" class="btn btn-danger btn-sm d-inline-flex align-items-center gap-1 fw-semibold py-1" onclick="return confirm('Apakah Anda yakin ingin menghapus plot jadwal {{ $row->hari }} {{ $row->pasaran }} untuk {{ $row->warga->nama }}?')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2 text-black-50"></i>
                                    Belum ada plot jadwal jimpitan yang diatur.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
