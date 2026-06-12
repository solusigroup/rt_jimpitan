@extends('layouts.app')

@section('title', 'Jadwal Jimpitan Malam Ini')

@section('styles')
<style>
    .duty-card {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        background: #ffffff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }
    .duty-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(59, 130, 246, 0.08) !important;
        border-color: rgba(59, 130, 246, 0.25);
    }
    .avatar-container {
        transition: all 0.3s ease;
    }
    .duty-card:hover .avatar-container {
        transform: scale(1.05);
    }
    .hover-scale {
        transition: all 0.2s ease;
    }
    .hover-scale:hover {
        transform: scale(1.05);
    }
    
    .button-belum {
        color: #64748b;
        background: transparent;
        border: none;
    }
    .button-belum.active {
        background-color: #f59e0b !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }
    .button-belum:not(.active):hover {
        background-color: #f1f5f9;
        color: #1e293b;
    }
    
    .button-sudah {
        color: #64748b;
        background: transparent;
        border: none;
    }
    .button-sudah.active {
        background-color: #10b981 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }
    .button-sudah:not(.active):hover {
        background-color: #f1f5f9;
        color: #1e293b;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4);
        }
        70% {
            box-shadow: 0 0 0 8px rgba(245, 158, 11, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
        }
    }
    .pulse-warning {
        animation: pulse 2s infinite;
    }
</style>
@endsection

@section('content')
<div class="container py-2">
    <!-- Header Banner -->
    <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
        <div class="card-body text-white text-center py-4 px-3" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6) !important;">
            <h3 class="fw-bold mb-1">Jadwal Jimpitan Malam Ini</h3>
            <h5 class="fw-medium text-white-50 mb-3">{{ Carbon\Carbon::parse($hari_ini)->translatedFormat('d F Y') }}</h5>
            <span class="badge bg-warning text-dark fs-5 py-2 px-4 shadow-sm fw-bold rounded-pill">{{ $weton['hari'] }} {{ $weton['pasaran'] }}</span>
        </div>
    </div>

    <!-- On-Duty Citizens Cards Section -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-0 py-3 fw-bold text-dark fs-5 d-flex align-items-center gap-2">
            <span class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary d-inline-flex">
                <i class="bi bi-shield-check"></i>
            </span>
            <span>Petugas Piket Jimpitan Malam Ini:</span>
        </div>
        <div class="card-body p-4 bg-light bg-opacity-25 rounded-bottom-4">
            <div class="row justify-content-center g-4">
                @forelse ($piketMalamIni as $warga)
                    @php
                        $belumActive = ($warga->status == 'Belum Dikerjakan') ? 'active' : '';
                        $sudahActive = ($warga->status == 'Sudah Dikerjakan') ? 'active' : '';
                        
                        // Initials for avatar fallback
                        $nameParts = explode(' ', $warga->nama);
                        if (count($nameParts) >= 2) {
                            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                        } else {
                            $initials = strtoupper(substr($warga->nama, 0, 2));
                        }
                    @endphp
                    <div class="col-md-10 col-lg-8">
                        <div class="card duty-card shadow-sm border-0 p-3">
                            <div class="card-body p-2">
                                <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start text-center text-sm-start gap-4 py-2">
                                    <!-- Avatar Section -->
                                    @if (!empty($warga->foto) && File::exists(public_path('uploads/' . $warga->foto)))
                                        <img src="{{ asset('uploads/' . $warga->foto) }}" class="avatar-container rounded-circle shadow-sm object-fit-cover border" style="width: 80px; height: 80px; min-width: 80px;" alt="{{ $warga->nama }}">
                                    @else
                                        <div class="avatar-container bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 80px; height: 80px; min-width: 80px; font-size: 1.8rem; letter-spacing: 0.5px;">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                    
                                    <!-- Info Section -->
                                    <div class="flex-grow-1 w-100">
                                        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 mb-2">
                                            <h3 class="fw-bold text-dark mb-0 fs-3">{{ $warga->nama }}</h3>
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1.5 fw-bold fs-6 rounded-pill">
                                                <i class="bi bi-house-door-fill me-1"></i>No. {{ $warga->no_rumah }}
                                            </span>
                                        </div>
                                        
                                        <!-- Contact and Status Row -->
                                        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 mt-3">
                                            <div>
                                                @if (!empty($warga->no_wa))
                                                    <a href="https://wa.me/{{ $warga->no_wa }}" target="_blank" class="btn btn-outline-success btn-sm px-3 rounded-pill fw-semibold d-inline-flex align-items-center gap-1 hover-scale py-1">
                                                        <i class="bi bi-whatsapp"></i> Hubungi WA
                                                    </a>
                                                @else
                                                    <span class="text-muted small italic" style="font-size: 0.85rem;"><i class="bi bi-telephone-x me-1"></i>No WA tidak tersedia</span>
                                                @endif
                                            </div>
                                            <div id="badge-container-{{ $warga->id }}">
                                                @if ($warga->status == 'Sudah Dikerjakan')
                                                    <span class='badge bg-success text-white px-3 py-2 fw-semibold rounded-pill d-inline-flex align-items-center gap-1.5'><i class='bi bi-check-all fs-6'></i> Sudah Selesai (Rp {{ number_format($warga->nominal, 0, ',', '.') }})</span>
                                                @else
                                                    <span class='badge bg-warning text-dark px-3 py-2 fw-semibold rounded-pill d-inline-flex align-items-center gap-1.5 pulse-warning'><i class='bi bi-clock-history fs-6'></i> Belum Selesai</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="text-black-50 my-3 opacity-25">
                                
                                <!-- Interactive Action Toggles -->
                                <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-3 p-2 bg-light rounded-3">
                                    <span class="text-secondary small fw-bold ps-2 d-inline-flex align-items-center gap-1.5 justify-content-center justify-content-sm-start">
                                        <i class="bi bi-shield-check text-primary fs-5"></i> Perbarui Status Pengerjaan:
                                    </span>
                                    <div class="btn-group rounded-pill overflow-hidden shadow-sm" role="group" style="border: 1px solid rgba(0,0,0,0.08); background: #ffffff;">
                                        <button type="button" id="btn-belum-{{ $warga->id }}" class="btn btn-sm px-2 px-sm-4 py-2 fw-bold text-nowrap d-flex align-items-center gap-1 gap-sm-1.5 transition-all button-belum {{ $belumActive }}" onclick="updateStatus({{ $warga->id }}, 'Belum Dikerjakan', '{{ $warga->nama }}')">
                                            <i class="bi bi-clock-history"></i> Belum Selesai
                                        </button>
                                        <button type="button" id="btn-sudah-{{ $warga->id }}" class="btn btn-sm px-2 px-sm-4 py-2 fw-bold text-nowrap d-flex align-items-center gap-1 gap-sm-1.5 transition-all button-sudah {{ $sudahActive }}" onclick="openNominalModal({{ $warga->id }}, '{{ $warga->nama }}')">
                                            <i class="bi bi-check-circle-fill"></i> Sudah Selesai
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="bi bi-calendar-x fs-1 d-block mb-3 text-black-50 opacity-50"></i>
                        <p class="fs-5 mb-0 fw-semibold">Tidak ada jadwal bertugas jimpitan malam ini.</p>
                        <p class="small text-black-50 mt-1">Selamat beristirahat, lingkungan aman terkendali.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Floating Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    <div id="statusToast" class="toast align-items-center text-white border-0 shadow-lg rounded-3" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2 py-3 px-3">
                <i class="bi bi-check-circle-fill fs-5" id="toastIcon"></i>
                <span id="toastMessage" class="fw-semibold">Status jimpitan berhasil diperbarui!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Modal Input Nominal Jimpitan -->
<div class="modal fade" id="nominalModal" tabindex="-1" aria-labelledby="nominalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white py-3 border-0 rounded-top-4">
                <h5 class="modal-title fw-bold" id="nominalModalLabel"><i class="bi bi-cash-coin me-1"></i> Input Uang Jimpitan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="small text-secondary fw-semibold">Nama Petugas Ronda</label>
                    <h5 class="fw-bold text-dark mb-0" id="modalWargaNama">-</h5>
                </div>
                <div class="mb-3">
                    <label for="modalNominalInput" class="form-label small text-secondary fw-semibold">Jumlah Jimpitan (Rp)</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-light fw-bold text-secondary">Rp</span>
                        <input type="number" id="modalNominalInput" class="form-control fw-bold text-success" value="1000" min="0">
                    </div>
                </div>
            </div>
            <div class="modal-footer p-3 border-0 bg-light rounded-bottom-4 d-flex">
                <button type="button" class="btn btn-outline-secondary w-50 fw-semibold rounded-pill py-2" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success w-50 fw-bold rounded-pill py-2" id="btnConfirmNominal">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentWargaId = 0;
let currentWargaNama = '';
let bootstrapModalInstance = null;

function openNominalModal(wargaId, namaWarga) {
    currentWargaId = wargaId;
    currentWargaNama = namaWarga;
    
    document.getElementById('modalWargaNama').textContent = namaWarga;
    document.getElementById('modalNominalInput').value = '1000';
    
    const modalEl = document.getElementById('nominalModal');
    if (!bootstrapModalInstance) {
        bootstrapModalInstance = new bootstrap.Modal(modalEl);
    }
    bootstrapModalInstance.show();
}

document.getElementById('btnConfirmNominal').addEventListener('click', function() {
    const nominal = parseInt(document.getElementById('modalNominalInput').value) || 0;
    if (bootstrapModalInstance) {
        bootstrapModalInstance.hide();
    }
    updateStatus(currentWargaId, 'Sudah Dikerjakan', currentWargaNama, nominal);
});

function updateStatus(wargaId, newStatus, namaWarga, nominal = 0) {
    const tanggal = '{{ $hari_ini }}';
    
    const btnBelum = document.getElementById('btn-belum-' + wargaId);
    const btnSudah = document.getElementById('btn-sudah-' + wargaId);
    if(btnBelum) btnBelum.disabled = true;
    if(btnSudah) btnSudah.disabled = true;
    
    fetch('{{ route("api.update_status") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            warga_id: wargaId,
            tanggal: tanggal,
            status: newStatus,
            nominal: nominal
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Gagal memperbarui status');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            if (newStatus === 'Sudah Dikerjakan') {
                btnBelum.classList.remove('active');
                btnSudah.classList.add('active');
                
                const formattedNominal = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(nominal).replace('IDR', 'Rp');
                
                const badgeContainer = document.getElementById('badge-container-' + wargaId);
                badgeContainer.innerHTML = `<span class="badge bg-success text-white px-3 py-2 fw-semibold rounded-pill d-inline-flex align-items-center gap-1.5"><i class="bi bi-check-all fs-6"></i> Sudah Selesai (${formattedNominal})</span>`;
                
                showToast('Status piket ' + namaWarga + ' diperbarui menjadi Sudah Selesai (' + formattedNominal + ')!', 'success');
            } else {
                btnSudah.classList.remove('active');
                btnBelum.classList.add('active');
                
                const badgeContainer = document.getElementById('badge-container-' + wargaId);
                badgeContainer.innerHTML = `<span class="badge bg-warning text-dark px-3 py-2 fw-semibold rounded-pill d-inline-flex align-items-center gap-1.5 pulse-warning"><i class="bi bi-clock-history fs-6"></i> Belum Selesai</span>`;
                
                showToast('Status piket ' + namaWarga + ' diperbarui menjadi Belum Selesai.', 'success');
            }
        } else {
            showToast('Gagal: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Gagal terhubung ke server atau terjadi error database.', 'danger');
    })
    .finally(() => {
        if(btnBelum) btnBelum.disabled = false;
        if(btnSudah) btnSudah.disabled = false;
    });
}

function showToast(message, type = 'success') {
    const toastEl = document.getElementById('statusToast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    toastMessage.textContent = message;
    
    const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
    
    if (type === 'success') {
        toastEl.classList.remove('bg-danger');
        toastEl.classList.add('bg-success');
        toastIcon.className = 'bi bi-check-circle-fill fs-5';
    } else {
        toastEl.classList.remove('bg-success');
        toastEl.classList.add('bg-danger');
        toastIcon.className = 'bi bi-exclamation-triangle-fill fs-5';
    }
    
    toast.show();
}
</script>
@endsection
