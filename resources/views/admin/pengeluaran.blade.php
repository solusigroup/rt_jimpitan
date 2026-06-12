@extends('layouts.app')

@section('title', 'Kelola Pengeluaran Kas')

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

    <div class="row">
        <!-- Sidebar: Atur Saldo & Catat Baru -->
        <div class="col-md-4 mb-4">
            <!-- Saldo Awal -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-dark text-white fw-bold">Atur Saldo Awal Kas</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.pengeluaran.saldo_awal') }}">
                        @csrf
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="saldo_awal" class="form-control" value="{{ old('saldo_awal', $setting->saldo_awal) }}" required>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Catat Pengeluaran -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white fw-bold">Catat Pengeluaran Baru</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.pengeluaran.store') }}">
                        @csrf
                        <div class="mb-2">
                            <label class="small fw-bold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-2">
                            <label class="small fw-bold">Keperluan / Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" placeholder="Misal: Beli Lampu Pos Ronda" value="{{ old('keterangan') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">Nominal Pengeluaran (Rp)</label>
                            <input type="number" name="nominal" class="form-control" placeholder="Contoh: 75000" value="{{ old('nominal') }}" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 fw-bold">Simpan Pengeluaran</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Riwayat Pengeluaran -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold text-danger">Daftar Kas Keluar</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th class="text-end">Nominal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expenses as $row)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                                        <td>{{ $row->keterangan }}</td>
                                        <td class="text-end text-danger fw-bold">Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.pengeluaran.delete', $row->id) }}" class="btn btn-link text-danger p-0 text-decoration-none small" onclick="return confirm('Hapus catatan pengeluaran ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Belum ada data pengeluaran.</td>
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
