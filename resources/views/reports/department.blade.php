@extends('layouts.app')
@section('title', 'Laporan per Departemen')
@section('page-title', 'Laporan per Departemen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">{{ now()->translatedFormat('F Y') }}</p>
    <a href="{{ route('reports.monthly') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Laporan Bulanan</a>
</div>
<div class="row g-3">
    @forelse($departments as $dept)
    <div class="col-md-6">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div><h6 class="fw-bold mb-1">{{ $dept->name }}</h6><span class="badge bg-primary rounded-pill">{{ $dept->code }}</span></div>
                    <div class="text-muted small">{{ $dept->stats['total_employees'] }} pegawai</div>
                </div>
                <div class="row g-2">
                    <div class="col-6"><div class="p-2 rounded" style="background:#e8f5e9;"><div class="small" style="color:#2e7d32;">Hadir</div><div class="fw-bold fs-5" style="color:#2e7d32;">{{ $dept->stats['present'] }}</div></div></div>
                    <div class="col-6"><div class="p-2 rounded" style="background:#fff8e1;"><div class="small" style="color:#f57f17;">Terlambat</div><div class="fw-bold fs-5" style="color:#f57f17;">{{ $dept->stats['late'] }}</div></div></div>
                    <div class="col-6"><div class="p-2 rounded" style="background:#ffebee;"><div class="small" style="color:#c62828;">Tidak Hadir</div><div class="fw-bold fs-5" style="color:#c62828;">{{ $dept->stats['absent'] }}</div></div></div>
                    <div class="col-6"><div class="p-2 rounded" style="background:#e3f2fd;"><div class="small" style="color:#1565c0;">Cuti</div><div class="fw-bold fs-5" style="color:#1565c0;">{{ $dept->stats['leave'] }}</div></div></div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">Belum ada departemen.</div>
    @endforelse
</div>
@endsection
