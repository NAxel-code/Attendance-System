@extends('layouts.app')
@section('title', 'Laporan Bulanan')
@section('page-title', 'Laporan Bulanan')

@section('content')
<div class="card border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.monthly') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-medium">Bulan</label>
                <select name="month" class="form-select form-select-sm">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium">Tahun</label>
                <select name="year" class="form-select form-select-sm">
                    @foreach(range(now()->year - 2, now()->year) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-medium">Departemen</label>
                <select name="department_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('reports.monthly') }}" class="btn btn-light btn-sm ms-1">Reset</a>
            </div>
            <div class="col-md-auto ms-auto">
                <a href="{{ route('reports.department') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-diagram-3 me-1"></i>Per Departemen</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="card border-0" style="border-left:4px solid #2e7d32 !important;"><div class="card-body py-3"><div class="text-muted small">Total Hadir</div><div class="fw-bold fs-4" style="color:#2e7d32;">{{ $summary['present'] }}</div></div></div></div>
    <div class="col-6 col-md-3"><div class="card border-0" style="border-left:4px solid #f57f17 !important;"><div class="card-body py-3"><div class="text-muted small">Total Terlambat</div><div class="fw-bold fs-4" style="color:#f57f17;">{{ $summary['late'] }}</div></div></div></div>
    <div class="col-6 col-md-3"><div class="card border-0" style="border-left:4px solid #c62828 !important;"><div class="card-body py-3"><div class="text-muted small">Tidak Hadir</div><div class="fw-bold fs-4" style="color:#c62828;">{{ $summary['absent'] }}</div></div></div></div>
    <div class="col-6 col-md-3"><div class="card border-0" style="border-left:4px solid #1565c0 !important;"><div class="card-body py-3"><div class="text-muted small">Total Cuti</div><div class="fw-bold fs-4" style="color:#1565c0;">{{ $summary['leave'] }}</div></div></div></div>
</div>

<div class="card border-0">
    <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
        <h6 class="fw-semibold mb-0">Rekap — {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}</h6>
    </div>
    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Pegawai</th><th>Departemen</th>
                        <th class="text-center" style="color:#2e7d32;">Hadir</th>
                        <th class="text-center" style="color:#f57f17;">Terlambat</th>
                        <th class="text-center" style="color:#c62828;">Tidak Hadir</th>
                        <th class="text-center" style="color:#1565c0;">Cuti</th>
                        <th class="text-center pe-4">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                    @php
                        $present=$emp->attendances->where('status','present')->count();
                        $late=$emp->attendances->where('status','late')->count();
                        $absent=$emp->attendances->where('status','absent')->count();
                        $leave=$emp->attendances->where('status','leave')->count();
                    @endphp
                    <tr>
                        <td class="ps-4"><div class="fw-medium">{{ $emp->name }}</div><div class="text-muted small">{{ $emp->employee_code }}</div></td>
                        <td class="text-muted small">{{ $emp->department->name ?? '—' }}</td>
                        <td class="text-center fw-bold" style="color:#2e7d32;">{{ $present }}</td>
                        <td class="text-center fw-bold" style="color:#f57f17;">{{ $late }}</td>
                        <td class="text-center fw-bold" style="color:#c62828;">{{ $absent }}</td>
                        <td class="text-center fw-bold" style="color:#1565c0;">{{ $leave }}</td>
                        <td class="text-center text-muted pe-4">{{ $present+$late+$absent+$leave }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
