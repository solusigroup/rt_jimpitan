@extends('layouts.app')

@section('title', 'Laporan Pelaksanaan Harian')

@section('styles')
<style>
    .stat-card {
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 16px;
        background: #ffffff;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.01);
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }
    .log-card {
        border-radius: 0;
        transition: all 0.2s ease;
    }
    .log-card:hover {
        background-color: #f8fafc !important;
    }
    .log-icon {
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .compliance-bar {
        height: 8px;
        border-radius: 4px;
    }
</style>
@endsection

@section('content')
<div class="container py-2 mb-5">
    <!-- Header Banner -->
    <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
        <div class="card-body bg-dark text-white text-center py-4 px-3" style="background: linear-gradient(135deg, #0f172a, #1e293b) !important;">
            <span class="badge bg-warning text-dark px-3 py-1.5 fw-bold rounded-pill mb-2"><i class="bi bi-file-earmark-bar-graph-fill me-1"></i>LAPORAN PUBLIK</span>
            <h3 class="fw-bold mb-1">Laporan Pelaksanaan Jimpitan Harian</h3>
            <p class="text-white-50 small mb-0">Halaman transparansi pantauan riwayat pengambilan jimpitan RT 35 secara realtime.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-3 mb-4">
        <!-- Stat 1: Total Tugas -->
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 d-inline-flex fs-5">
                        <i class="bi bi-clipboard-data"></i>
                    </span>
                    <span class="text-secondary small fw-semibold">Total Tugas</span>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ $totalTugas }} <span class="fs-6 fw-normal text-muted">Hari</span></h3>
            </div>
        </div>

        <!-- Stat 2: Selesai -->
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="bg-success bg-opacity-10 text-success p-2 rounded-3 d-inline-flex fs-5">
                        <i class="bi bi-check-circle-fill"></i>
                    </span>
                    <span class="text-secondary small fw-semibold">Selesai</span>
                </div>
                <h3 class="fw-bold text-success mb-0">{{ $selesai }} <span class="fs-6 fw-normal text-muted">Hari</span></h3>
            </div>
        </div>

        <!-- Stat 3: Belum Selesai -->
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="bg-warning bg-opacity-10 text-warning p-2 rounded-3 d-inline-flex fs-5">
                        <i class="bi bi-hourglass-split"></i>
                    </span>
                    <span class="text-secondary small fw-semibold">Belum Selesai</span>
                </div>
                <h3 class="fw-bold text-warning mb-0">{{ $belumSelesai }} <span class="fs-6 fw-normal text-muted">Hari</span></h3>
            </div>
        </div>

        <!-- Stat 4: Kepatuhan -->
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="bg-info bg-opacity-10 text-info p-2 rounded-3 d-inline-flex fs-5">
                        <i class="bi bi-graph-up-arrow"></i>
                    </span>
                    <span class="text-secondary small fw-semibold">Kepatuhan</span>
                </div>
                <h3 class="fw-bold text-info mb-1">{{ $complianceRate }}%</h3>
                <div class="progress compliance-bar bg-light">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $complianceRate }}%" aria-valuenow="{{ $complianceRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <!-- Search -->
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="searchLogInput" class="form-control border-start-0 py-2" placeholder="Cari nama warga atau nomor rumah..." onkeyup="filterLogs()">
                    </div>
                </div>
                <!-- Status Filter -->
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-funnel text-muted"></i></span>
                        <select id="statusFilterSelect" class="form-select border-start-0 py-2" onchange="filterLogs()">
                            <option value="Semua">Semua Status</option>
                            <option value="Sudah Dikerjakan">Sudah Selesai</option>
                            <option value="Belum Dikerjakan">Belum Selesai</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs List Card -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 fw-bold text-dark fs-5 d-flex justify-content-between align-items-center">
            <span><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Tugas Harian</span>
            <span class="badge bg-secondary rounded-pill fw-semibold text-white fs-6">Total Laporan: {{ $totalTugas }}</span>
        </div>
        
        <div class="card-body p-0 border-top">
            <div id="logsContainer" class="d-flex flex-column">
                @forelse ($logs as $log)
                    @php
                        $timestamp = strtotime($log->tanggal);
                        $formattedDate = $log->hari_jawa . ' ' . $log->pasaran_jawa . ', ' . Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y');
                        $updateTime = Carbon\Carbon::parse($log->waktu_update)->format('H:i') . ' WIB';
                    @endphp
                    <div class="log-card p-3 border-bottom bg-white d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3" data-search="{{ strtolower($log->warga->nama . ' ' . $log->warga->no_rumah) }}" data-status="{{ $log->status }}">
                        <!-- Kiri: Weton Tanggal & Nama Warga -->
                        <div class="d-flex align-items-center gap-3">
                            <div class="log-icon bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px;">
                                <i class="bi bi-calendar3 fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark fs-6">{{ $formattedDate }}</div>
                                <div class="text-secondary small d-flex align-items-center gap-1.5 mt-0.5 flex-wrap">
                                    <i class="bi bi-person-fill text-muted"></i>
                                    <span class="fw-semibold text-dark">{{ $log->warga->nama }}</span>
                                    <span class="badge bg-light text-dark border px-2 py-0.5 small rounded-1">No. {{ $log->warga->no_rumah }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kanan: Status & Waktu Update -->
                        <div class="d-flex flex-row flex-md-column align-items-center align-items-md-end justify-content-between gap-2 border-top border-md-0 pt-2 pt-md-0">
                            <div>
                                @if ($log->status == 'Sudah Dikerjakan')
                                    <span class="badge bg-success text-white px-3 py-1.5 fw-semibold rounded-pill d-inline-flex align-items-center gap-1.5"><i class="bi bi-check-all fs-6"></i> Sudah Selesai</span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-1.5 fw-semibold rounded-pill d-inline-flex align-items-center gap-1.5"><i class="bi bi-clock-history fs-6"></i> Belum Selesai</span>
                                @endif
                            </div>
                            <div class="text-secondary small" style="font-size: 0.78rem;">
                                <i class="bi bi-clock me-1"></i>Waktu lapor: {{ $updateTime }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted" id="initialNoLogs">
                        <i class="bi bi-folder-x fs-1 d-block mb-3 text-black-50 opacity-50"></i>
                        <p class="fs-5 mb-0 fw-semibold">Belum ada riwayat pelaksanaan jimpitan.</p>
                        <p class="small text-black-50 mt-1">Pengurus belum meng-update status piket harian.</p>
                    </div>
                @endforelse
                
                <!-- JS Realtime search no result state -->
                <div class="col-12 text-center py-5 text-muted d-none" id="noLogsResult">
                    <i class="bi bi-search fs-1 d-block mb-3 text-black-50 opacity-50"></i>
                    <p class="fs-5 mb-0 fw-semibold">Tidak menemukan hasil pencarian.</p>
                    <p class="small text-black-50 mt-1">Coba gunakan nama warga atau nomor rumah lainnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function filterLogs() {
    const searchQuery = document.getElementById('searchLogInput').value.toLowerCase();
    const statusQuery = document.getElementById('statusFilterSelect').value;
    const cards = document.querySelectorAll('.log-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const searchData = card.getAttribute('data-search').toLowerCase();
        const cardStatus = card.getAttribute('data-status');
        
        const matchesSearch = searchData.includes(searchQuery);
        const matchesStatus = (statusQuery === 'Semua' || cardStatus === statusQuery);
        
        if (matchesSearch && matchesStatus) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.setProperty('display', 'none', 'important');
        }
    });
    
    const noResultEl = document.getElementById('noLogsResult');
    if (visibleCount === 0 && cards.length > 0) {
        noResultEl.classList.remove('d-none');
    } else {
        noResultEl.classList.add('d-none');
    }
}
</script>
@endsection
