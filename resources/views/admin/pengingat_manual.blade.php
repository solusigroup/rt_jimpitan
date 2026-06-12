@extends('layouts.app')

@section('title', 'Kirim Pengingat Manual')

@section('content')
<div class="container mt-3 mt-md-5 mb-4" style="max-width: 600px;">
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-success text-white text-center py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-whatsapp me-2"></i>Antrean Pesan WhatsApp</h5>
            <small>Jadwal Hari Ini: <strong>{{ $hari }} {{ $pasaran }}</strong></small>
        </div>
        <div class="card-body p-4">
            @if (!empty($chats))
                <p class="text-muted text-center small mb-4">Silakan klik tombol <strong>Kirim ke WA</strong> satu per satu untuk setiap warga di bawah ini:</p>

                @php $no = 1; @endphp
                @foreach ($chats as $chat)
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center p-3 mb-3 border rounded-3 bg-white shadow-sm gap-3">
                        <div class="text-center text-sm-start">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary mb-1">Warga #{{ $no++ }}</span>
                            <h5 class="mb-0 fw-bold text-dark">
                                {{ $chat['nama'] }}
                            </h5>
                            <small class="text-muted">{{ $chat['no_wa'] }}</small>
                        </div>
                        <a href="{{ $chat['link'] }}" target="_blank" class="btn btn-success btn-lg py-2.5 px-4 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-whatsapp fs-5"></i> Kirim ke WA →
                        </a>
                    </div>
                @endforeach
            @else
                <div class="alert alert-warning text-center mb-0">Tidak ada jadwal petugas jimpitan untuk hari ini.</div>
            @endif

            <div class="text-center mt-4 pt-3 border-top">
                <a href="{{ route('admin.pengingat') }}" class="btn btn-secondary btn-sm">← Kembali ke Panel Pengingat</a>
            </div>
        </div>
    </div>
</div>
@endsection
