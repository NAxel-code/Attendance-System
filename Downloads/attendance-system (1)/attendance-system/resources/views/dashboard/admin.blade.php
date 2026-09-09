@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold text-dark mb-1">Selamat datang, {{ auth()->user()->name }}! 👋</h4>
    <p class="text-muted mb-0">{{ now()->translatedFormat('l, d F Y') }} — Panel Administrator</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 h-100" style="border-left:4px solid #1a237e !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Pegawai</span>
                    <div style="width:36px;height:36px;border-radius:8px;background:#e8eaf6;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-people" style="color:#1a237e;"></i>
                    </div>
                </div>
                <div class="fw-bold" style="font-size:2rem;color:#1a237e;">{{ $stats['total_employees'] }}</div>
                <div class="text-muted" style="font-size:.78rem;">pegawai aktif</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 h-100" style="border-left:4px solid #2e7d32 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Hadir Hari Ini</span>
                    <div style="width:36px;height:36px;border-radius:8px;background:#e8f5e9;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-check-circle" style="color:#2e7d32;"></i>
                    </div>
                </div>
                <div class="fw-bold" style="font-size:2rem;color:#2e7d32;">{{ $stats['present_today'] }}</div>
                <div class="text-muted" style="font-size:.78rem;">pegawai hadir</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 h-100" style="border-left:4px solid #f57f17 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Terlambat</span>
                    <div style="width:36px;height:36px;border-radius:8px;background:#fff8e1;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-clock-history" style="color:#f57f17;"></i>
                    </div>
                </div>
                <div class="fw-bold" style="font-size:2rem;color:#f57f17;">{{ $stats['late_today'] }}</div>
                <div class="text-muted" style="font-size:.78rem;">hari ini</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 h-100" style="border-left:4px solid #c62828 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Tidak Hadir</span>
                    <div style="width:36px;height:36px;border-radius:8px;background:#ffebee;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-x-circle" style="color:#c62828;"></i>
                    </div>
                </div>
                <div class="fw-bold" style="font-size:2rem;color:#c62828;">{{ $stats['absent_today'] }}</div>
                <div class="text-muted" style="font-size:.78rem;">hari ini</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 h-100" style="border-left:4px solid #6a1b9a !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Total Departemen</span>
                    <div style="width:36px;height:36px;border-radius:8px;background:#f3e5f5;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-diagram-3" style="color:#6a1b9a;"></i>
                    </div>
                </div>
                <div class="fw-bold" style="font-size:2rem;color:#6a1b9a;">{{ $stats['total_departments'] }}</div>
                <div class="text-muted" style="font-size:.78rem;">departemen</div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 h-100">
            <div class="card-body">
                <p class="text-muted small fw-medium mb-3">Menu Cepat</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('employees.create') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-person-plus me-1"></i>Tambah Pegawai</a>
                    <a href="{{ route('attendances.create') }}" class="btn btn-sm btn-outline-success"><i class="bi bi-calendar-plus me-1"></i>Catat Absensi</a>
                    <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-people me-1"></i>Data Pegawai</a>
                    <a href="{{ route('reports.monthly') }}" class="btn btn-sm btn-outline-info"><i class="bi bi-bar-chart me-1"></i>Laporan Bulanan</a>
                    <a href="{{ route('departments.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-diagram-3 me-1"></i>Departemen</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0">
    <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0">Absensi Terbaru</h6>
        <a href="{{ route('attendances.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Pegawai</th>
                        <th>Departemen</th>
                        <th>Tanggal</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAttendances as $att)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-medium">{{ $att->employee->name }}</div>
                            <div class="text-muted small">{{ $att->employee->employee_code }}</div>
                        </td>
                        <td class="text-muted small">{{ $att->employee->department->name ?? '—' }}</td>
                        <td>{{ $att->date->translatedFormat('d M Y') }}</td>
                        <td>{{ $att->check_in ?? '—' }}</td>
                        <td>{{ $att->check_out ?? '—' }}</td>
                        <td class="pe-4">
                            @php
                                $colors = ['present'=>['bg'=>'#e8f5e9','color'=>'#2e7d32','label'=>'Hadir'],'late'=>['bg'=>'#fff8e1','color'=>'#f57f17','label'=>'Terlambat'],'absent'=>['bg'=>'#ffebee','color'=>'#c62828','label'=>'Tidak Hadir'],'leave'=>['bg'=>'#e3f2fd','color'=>'#1565c0','label'=>'Cuti']];
                                $s = $colors[$att->status] ?? ['bg'=>'#f5f5f5','color'=>'#666','label'=>ucfirst($att->status)];
                            @endphp
                            <span class="badge rounded-pill px-3 py-1" style="background:{{ $s['bg'] }};color:{{ $s['color'] }};font-size:.78rem;">{{ $s['label'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada data absensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
