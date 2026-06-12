@extends('layouts.app')

@section('title', 'Jadwal Lengkap Jimpitan')

@section('styles')
<style>
    .matrix-table th {
        vertical-align: middle;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .matrix-table td {
        min-width: 140px;
        vertical-align: top;
        background-color: #ffffff;
    }
    .warga-card {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 8px;
        background-color: #ffffff;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .warga-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        border-color: #f59e0b;
    }
    .day-cell {
        background-color: #f1f5f9 !important;
        font-weight: 700;
        color: #1e293b;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-md-5 mt-2 mb-5">
    <!-- Header Pencarian & Info -->
    <div class="card shadow-sm border-0 mb-4 rounded-3">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-md-7">
                    <h4 class="fw-bold text-dark mb-1">
                        <i class="bi bi-calendar3-week text-primary me-2"></i>Tabel Jadwal Jimpitan RT
                    </h4>
                    <p class="text-muted mb-0 small">Berikut adalah matriks distribusi seluruh jadwal pengambilan jimpitan berdasarkan Hari dan Pasaran Jawa.</p>
                </div>
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="searchInput" class="form-control border-start-0 py-2.5" placeholder="Masukkan nama warga atau nomor rumah..." onkeyup="filterSchedule()">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Matriks Jadwal -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered matrix-table mb-0 align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th class="py-3 bg-dark text-start ps-4" style="width: 12%;">HARI / PASARAN</th>
                            @foreach ($pasaran_list as $p)
                                <th class="py-3 text-uppercase" style="width: 17.6%;">{{ $p }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hari_list as $h)
                            <tr>
                                <td class="day-cell text-start ps-4 py-4">{{ $h }}</td>
                                @foreach ($pasaran_list as $p)
                                    <td class="p-2">
                                        @if (!empty($matrix[$h][$p]))
                                            @foreach ($matrix[$h][$p] as $warga)
                                                <div class="warga-card p-2.5 text-center shadow-none mb-1 d-block" data-search="{{ strtolower($warga['nama'] . ' ' . $warga['no_rumah']) }}">
                                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $warga['nama'] }}</div>
                                                    <div class="text-secondary small mt-0.5"><i class="bi bi-house-door-fill text-black-50 me-1"></i>No. {{ $warga['no_rumah'] }}</div>
                                                    @if (!empty($warga['no_wa']))
                                                        <a href="https://wa.me/{{ $warga['no_wa'] }}" target="_blank" class="btn btn-link text-success p-0 btn-sm mt-1 fw-semibold text-decoration-none d-inline-flex align-items-center gap-1">
                                                            <i class="bi bi-whatsapp"></i> Hubungi
                                                        </a>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted small italic py-3 d-block text-black-50"><i class="bi bi-dash-circle me-1"></i>Kosong</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function filterSchedule() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let cards = document.querySelectorAll('.warga-card');
    
    cards.forEach(card => {
        let searchData = card.getAttribute('data-search');
        
        if (searchData.includes(input)) {
            card.style.opacity = '1';
            if (input !== '') {
                card.style.borderColor = '#f59e0b';
                card.style.backgroundColor = '#fffbeb';
                card.style.transform = 'scale(1.05)';
            } else {
                card.style.borderColor = 'rgba(0, 0, 0, 0.08)';
                card.style.backgroundColor = '#ffffff';
                card.style.transform = 'scale(1)';
            }
        } else {
            if (input !== '') {
                card.style.opacity = '0.25';
                card.style.borderColor = 'rgba(0, 0, 0, 0.08)';
                card.style.backgroundColor = '#ffffff';
                card.style.transform = 'scale(0.95)';
            } else {
                card.style.opacity = '1';
                card.style.borderColor = 'rgba(0, 0, 0, 0.08)';
                card.style.backgroundColor = '#ffffff';
                card.style.transform = 'scale(1)';
            }
        }
    });
}
</script>
@endsection
