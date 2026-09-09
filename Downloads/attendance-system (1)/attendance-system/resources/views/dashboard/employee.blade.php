@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php
    $employee = auth()->user()->employee;
    $todayRecord = $employee?->todayAttendance;
@endphp

<div class="mb-4">
    <h4 class="fw-bold text-dark mb-1">Selamat datang, {{ auth()->user()->name }}! 👋</h4>
    <p class="text-muted mb-0">
        {{ now()->translatedFormat('l, d F Y') }} &mdash;
        @if($todayRecord)
            @if($todayRecord->check_out)
                <span class="text-success fw-medium">Anda sudah selesai bekerja hari ini.</span>
            @else
                <span class="text-primary fw-medium">Anda sedang bekerja hari ini.</span>
            @endif
        @else
            <span class="text-warning fw-medium">Anda belum melakukan check-in hari ini.</span>
        @endif
    </p>
</div>

@if($employee)
<div class="card mb-4 border-0" style="background: linear-gradient(135deg, #1a237e 0%, #3949ab 100%);">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-6 text-white mb-3 mb-md-0">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:52px;height:52px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-badge" style="font-size:1.5rem;color:#fff;"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:1.1rem;">{{ $employee->name }}</div>
                        <div style="color:rgba(255,255,255,.7);font-size:.85rem;">{{ $employee->employee_code }} &bull; {{ $employee->position }}</div>
                        <div style="color:rgba(255,255,255,.6);font-size:.8rem;">{{ $employee->department->name ?? '-' }}</div>
                    </div>
                </div>
                <div class="d-flex gap-4">
                    <div>
                        <div style="color:rgba(255,255,255,.6);font-size:.75rem;text-transform:uppercase;">Jam Masuk</div>
                        <div class="fw-bold" style="font-size:1.4rem;">{{ $todayRecord?->check_in ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="color:rgba(255,255,255,.6);font-size:.75rem;text-transform:uppercase;">Jam Keluar</div>
                        <div class="fw-bold" style="font-size:1.4rem;">{{ $todayRecord?->check_out ?? '—' }}</div>
                    </div>
                    @if($todayRecord)
                    <div>
                        <div style="color:rgba(255,255,255,.6);font-size:.75rem;text-transform:uppercase;">Status</div>
                        <div class="fw-bold" style="font-size:1rem;">
                            @if($todayRecord->status === 'present') <span class="badge bg-success">Hadir</span>
                            @elseif($todayRecord->status === 'late') <span class="badge bg-warning text-dark">Terlambat</span>
                            @else <span class="badge bg-secondary">{{ $todayRecord->status_label }}</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="text-white mb-3">
                    <div style="font-size:2.5rem;font-weight:700;letter-spacing:2px;" id="clock">--:--:--</div>
                    <div style="color:rgba(255,255,255,.6);font-size:.85rem;">Waktu Sekarang</div>
                </div>
                @if(! $todayRecord)
                    <form method="POST" action="{{ route('attendances.check-in') }}">
                        @csrf
                        <button type="submit" class="btn btn-light fw-semibold px-4 py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Check In Sekarang
                        </button>
                    </form>
                @elseif(! $todayRecord->check_out)
                    <form method="POST" action="{{ route('attendances.check-out') }}">
                        @csrf
                        <button type="submit" class="btn btn-warning fw-semibold px-4 py-2">
                            <i class="bi bi-box-arrow-right me-2"></i>Check Out Sekarang
                        </button>
                    </form>
                @else
                    <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill" style="background:rgba(255,255,255,.15);color:#fff;">
                        <i class="bi bi-check-circle-fill text-success"></i>
                        <span class="fw-medium">Selesai hari ini</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<h6 class="fw-semibold text-muted text-uppercase mb-3" style="font-size:.75rem;letter-spacing:.08em;">Rekap Bulan Ini — {{ now()->translatedFormat('F Y') }}</h6>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card h-100 border-0" style="border-left:4px solid #2e7d32 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Hadir</span>
                    <div style="width:36px;height:36px;border-radius:8px;background:#e8f5e9;display:flex;align-items:center;justify-content:center;"><i class="bi bi-check-circle" style="color:#2e7d32;"></i></div>
                </div>
                <div class="fw-bold" style="font-size:2rem;color:#2e7d32;">{{ $monthlyStats['present'] ?? 0 }}</div>
                <div class="text-muted" style="font-size:.78rem;">hari hadir</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card h-100 border-0" style="border-left:4px solid #f57f17 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Terlambat</span>
                    <div style="width:36px;height:36px;border-radius:8px;background:#fff8e1;display:flex;align-items:center;justify-content:center;"><i class="bi bi-clock-history" style="color:#f57f17;"></i></div>
                </div>
                <div class="fw-bold" style="font-size:2rem;color:#f57f17;">{{ $monthlyStats['late'] ?? 0 }}</div>
                <div class="text-muted" style="font-size:.78rem;">hari terlambat</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card h-100 border-0" style="border-left:4px solid #c62828 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Tidak Hadir</span>
                    <div style="width:36px;height:36px;border-radius:8px;background:#ffebee;display:flex;align-items:center;justify-content:center;"><i class="bi bi-x-circle" style="color:#c62828;"></i></div>
                </div>
                <div class="fw-bold" style="font-size:2rem;color:#c62828;">{{ $monthlyStats['absent'] ?? 0 }}</div>
                <div class="text-muted" style="font-size:.78rem;">hari absen</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card h-100 border-0" style="border-left:4px solid #1565c0 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Cuti</span>
                    <div style="width:36px;height:36px;border-radius:8px;background:#e3f2fd;display:flex;align-items:center;justify-content:center;"><i class="bi bi-calendar-minus" style="color:#1565c0;"></i></div>
                </div>
                <div class="fw-bold" style="font-size:2rem;color:#1565c0;">{{ $monthlyStats['leave'] ?? 0 }}</div>
                <div class="text-muted" style="font-size:.78rem;">hari cuti</div>
            </div>
        </div>
    </div>
</div>

@if($employee)
<div class="card border-0">
    <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0">Riwayat Absensi Terakhir</h6>
        <a href="{{ route('attendances.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Hari</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th class="pe-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employee->attendances()->latest('date')->take(10)->get() as $att)
                    <tr>
                        <td class="ps-4 fw-medium">{{ $att->date->format('d M Y') }}</td>
                        <td class="text-muted">{{ $att->date->translatedFormat('l') }}</td>
                        <td>{{ $att->check_in ?? '—' }}</td>
                        <td>{{ $att->check_out ?? '—' }}</td>
                        <td class="text-muted small">
                            @if($att->check_in && $att->check_out)
                                @php $in=\Carbon\Carbon::parse($att->check_in);$out=\Carbon\Carbon::parse($att->check_out);$diff=$in->diff($out); @endphp
                                {{ $diff->h }}j {{ $diff->i }}m
                            @else —
                            @endif
                        </td>
                        <td>
                            @php $colors=['present'=>['bg'=>'#e8f5e9','color'=>'#2e7d32','label'=>'Hadir'],'late'=>['bg'=>'#fff8e1','color'=>'#f57f17','label'=>'Terlambat'],'absent'=>['bg'=>'#ffebee','color'=>'#c62828','label'=>'Tidak Hadir'],'leave'=>['bg'=>'#e3f2fd','color'=>'#1565c0','label'=>'Cuti']]; $s=$colors[$att->status]??['bg'=>'#f5f5f5','color'=>'#666','label'=>ucfirst($att->status)]; @endphp
                            <span class="badge rounded-pill px-3 py-1" style="background:{{ $s['bg'] }};color:{{ $s['color'] }};font-size:.78rem;">{{ $s['label'] }}</span>
                        </td>
                        <td class="text-muted small pe-4">{{ $att->notes ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-calendar-x fs-3 d-block mb-2"></i>Belum ada riwayat absensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function updateClock(){const n=new Date();const h=String(n.getHours()).padStart(2,'0');const m=String(n.getMinutes()).padStart(2,'0');const s=String(n.getSeconds()).padStart(2,'0');const el=document.getElementById('clock');if(el)el.textContent=h+':'+m+':'+s;}
updateClock();setInterval(updateClock,1000);
</script>
@endpush
