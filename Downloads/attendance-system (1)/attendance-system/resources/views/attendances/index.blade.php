@extends('layouts.app')
@section('title', 'Data Absensi')
@section('page-title', 'Data Absensi')

@section('content')
{{-- Check-in / Check-out Card (untuk pegawai biasa) --}}
@if(! auth()->user()->isAdmin())
    @php $employee = auth()->user()->employee; @endphp
    @if($employee)
        @php $today = $employee->todayAttendance; @endphp
        <div class="card mb-4">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1 fw-semibold">Kehadiran Hari Ini</h6>
                    <div class="text-muted small">
                        Masuk: <strong>{{ $today?->check_in ?? '—' }}</strong> &nbsp;|&nbsp;
                        Keluar: <strong>{{ $today?->check_out ?? '—' }}</strong>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    @if(! $today)
                        <form method="POST" action="{{ route('attendances.check-in') }}">
                            @csrf
                            <button class="btn btn-success"><i class="bi bi-box-arrow-in-right me-1"></i>Check In</button>
                        </form>
                    @elseif(! $today->check_out)
                        <form method="POST" action="{{ route('attendances.check-out') }}">
                            @csrf
                            <button class="btn btn-warning text-dark"><i class="bi bi-box-arrow-right me-1"></i>Check Out</button>
                        </form>
                    @else
                        <span class="badge bg-success py-2 px-3">Selesai hari ini</span>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endif

{{-- Filter & Tombol Tambah --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('attendances.index') }}" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-medium">Bulan</label>
                <select name="month" class="form-select form-select-sm">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
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
            @if(auth()->user()->isAdmin())
            <div class="col-md-3">
                <label class="form-label small fw-medium">Departemen</label>
                <select name="department_id" class="form-select form-select-sm">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-2">
                <label class="form-label small fw-medium">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                    <option value="late"    {{ request('status') == 'late'    ? 'selected' : '' }}>Terlambat</option>
                    <option value="absent"  {{ request('status') == 'absent'  ? 'selected' : '' }}>Tidak Hadir</option>
                    <option value="leave"   {{ request('status') == 'leave'   ? 'selected' : '' }}>Cuti</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
            @if(auth()->user()->isAdmin())
            <div class="col-md-auto ms-auto">
                <a href="{{ route('attendances.create') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Catat Absensi
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

{{-- Tabel Absensi --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        @if(auth()->user()->isAdmin())<th>Pegawai</th><th>Departemen</th>@endif
                        <th>Tanggal</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                    <tr>
                        <td class="ps-4 text-muted">{{ $attendances->firstItem() + $loop->index }}</td>
                        @if(auth()->user()->isAdmin())
                        <td>
                            <div class="fw-medium">{{ $att->employee->name }}</div>
                            <div class="text-muted small">{{ $att->employee->employee_code }}</div>
                        </td>
                        <td class="text-muted small">{{ $att->employee->department->name ?? '—' }}</td>
                        @endif
                        <td>{{ $att->date->translatedFormat('d M Y') }}</td>
                        <td>{{ $att->check_in ?? '—' }}</td>
                        <td>{{ $att->check_out ?? '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $att->status_color }} rounded-pill px-3 py-1" style="font-size:.78rem;">
                                {{ $att->status_label }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ Str::limit($att->notes, 40) }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('attendances.show', $att) }}" class="btn btn-sm btn-outline-secondary py-0">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('attendances.edit', $att) }}" class="btn btn-sm btn-outline-primary py-0 ms-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('attendances.destroy', $att) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus data absensi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-0 ms-1"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Tidak ada data absensi ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($attendances->hasPages())
    <div class="card-footer bg-transparent">
        {{ $attendances->links() }}
    </div>
    @endif
</div>
@endsection
