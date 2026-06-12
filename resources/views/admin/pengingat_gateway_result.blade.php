@extends('layouts.app')

@section('title', 'Laporan Pengiriman Gateway')

@section('content')
<div class="container mt-3 mt-md-5 mb-4" style="max-width: 600px;">
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-send-check-fill me-2"></i>Hasil Pengiriman Otomatis</h5>
        </div>
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3">Log Pengiriman Gateway:</h6>
            
            <div class="list-group mb-4" style="max-height: 300px; overflow-y: auto;">
                @forelse ($logs as $log)
                    <div class="list-group-item list-group-item-action small py-2">
                        @if (str_contains($log, 'Gagal'))
                            <i class="bi bi-x-circle-fill text-danger me-2"></i>
                        @else
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                        @endif
                        {{ $log }}
                    </div>
                @empty
                    <div class="list-group-item text-center text-muted">Tidak ada log pengiriman.</div>
                @endforelse
            </div>

            <div class="text-center pt-3 border-top">
                <a href="{{ route('admin.pengingat') }}" class="btn btn-secondary btn-sm">← Kembali ke Panel Pengingat</a>
            </div>
        </div>
    </div>
</div>
@endsection
