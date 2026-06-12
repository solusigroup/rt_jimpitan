@extends('layouts.app')

@section('title', 'Laporan Keuangan Kas')

@section('content')
<div class="container mt-4" style="max-width: 900px;">

    <!-- Stats Cards Row -->
    <div class="row mb-4 text-center">
        <div class="col-md-4 mb-2">
            <div class="card bg-white border-0 shadow-sm p-3">
                <span class="text-secondary small fw-bold text-uppercase">Saldo Awal & Jimpitan</span>
                <h4 class="text-primary fw-bold mt-1">Rp {{ number_format($saldoAwal + $totalPemasukan, 0, ',', '.') }}</h4>
                <small class="text-muted">Awal: Rp {{ number_format($saldoAwal, 0, ',', '.') }}</small>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card bg-white border-0 shadow-sm p-3">
                <span class="text-secondary small fw-bold text-uppercase">Total Pengeluaran</span>
                <h4 class="text-danger fw-bold mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
                <small class="text-muted">Dana Terpakai RT</small>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card bg-success text-white border-0 shadow-sm p-3">
                <span class="text-white-50 small fw-bold text-uppercase">SALDO AKHIR KAS</span>
                <h3 class="fw-bold mt-1">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>
                <small class="text-white-50">Siap Digunakan</small>
            </div>
        </div>
    </div>

    <!-- Details Tables Row -->
    <div class="row">
        <!-- Jimpitan Masuk -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">Riwayat Uang Jimpitan Masuk</div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0 small">
                        <thead>
                            <tr>
                                <th>Tanggal & Weton</th>
                                <th>Petugas</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($incomeLogs as $row)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark mb-0">{{ Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</div>
                                        <small class="text-secondary" style="font-size: 0.75rem;">{{ $row->weton_nama }}</small>
                                    </td>
                                    <td>{{ $row->warga->nama }}</td>
                                    <td class="text-end text-success fw-bold">Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Belum ada dana jimpitan masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Kas -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white fw-bold">Riwayat Pengeluaran Kas RT</div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0 small">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keperluan</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expenseLogs as $row)
                                <tr>
                                    <td>{{ Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $row->keterangan }}</td>
                                    <td class="text-end text-danger fw-bold">Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Belum ada data pengeluaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
