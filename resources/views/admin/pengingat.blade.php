@extends('layouts.app')

@section('title', 'Panel Pengingat WhatsApp')

@section('content')
<div class="container" style="max-width: 700px;">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white text-center py-3">
            <h5 class="mb-0 fw-bold">Kirim Pengingat Jimpitan Warga</h5>
            <small>Jadwal Hari Ini: <strong>{{ $hari }} {{ $pasaran }}</strong></small>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info small mb-4">
                <strong>Petunjuk Pak RT / Pengurus:</strong><br>
                Anda memiliki dua cara untuk mengirim pesan pengingat kepada warga yang bertugas malam ini:
                <hr class="my-2">
                1. <strong>🚀 Kirim via Gateway Otomatis (Node.js):</strong> Mengirim pesan secara instan di latar belakang menggunakan server WhatsApp lokal di port 3000.<br>
                2. <strong>📱 Kirim secara Manual (Tautan WA):</strong> Menampilkan antrean tombol WhatsApp manual. Klik tombol untuk membuka WhatsApp Web/App Anda yang sudah siap dengan draf pesan otomatis. 100% aman dari blokir.
            </div>

            <h6 class="fw-bold mb-3">Daftar Petugas Malam Ini:</h6>

            @if ($dutyList->isNotEmpty())
                <div class="list-group mb-4">
                    @foreach ($dutyList as $warga)
                        <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $warga->nama }}</h6>
                                <small class="text-muted">Rumah No. {{ $warga->no_rumah }} | WA: {{ $warga->no_wa ?? 'Tidak tersedia' }}</small>
                                @if ($warga->status_tugas == 'Sudah Selesai')
                                    <br><span class="badge bg-light text-success border border-success mt-1">💰 Kas: Rp {{ number_format($warga->nominal_jimpitan, 0, ',', '.') }}</span>
                                @endif
                            </div>

                            <div>
                                @if ($warga->status_tugas == 'Belum Selesai')
                                    <span class="badge bg-warning text-dark rounded-pill">Belum Selesai</span>
                                @else
                                    <span class="badge bg-success rounded-pill">✅ Selesai</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row g-2">
                    <div class="col-sm-6">
                        <a href="{{ route('admin.pengingat.gateway') }}" class="btn btn-primary btn-lg w-100 shadow-sm py-3 fw-bold fs-6">
                            🚀 GATEWAY OTOMATIS
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ route('admin.pengingat.manual') }}" class="btn btn-success btn-lg w-100 shadow-sm py-3 fw-bold fs-6">
                            📱 TAUTAN WA MANUAL
                        </a>
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-center py-4">
                    Tidak ada jadwal warga yang bertugas pada hari <strong>{{ $hari }} {{ $pasaran }}</strong>.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
