@extends('layouts.app')
@section('title', 'Detail Absensi')
@section('page-title', 'Detail Absensi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-4">Detail Absensi</h5>
                <table class="table table-borderless">
                    <tr><td class="text-muted" width="140">Pegawai</td><td class="fw-medium">{{ $attendance->employee->name }}</td></tr>
                    <tr><td class="text-muted">Kode</td><td>{{ $attendance->employee->employee_code }}</td></tr>
                    <tr><td class="text-muted">Departemen</td><td>{{ $attendance->employee->department->name ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Tanggal</td><td>{{ $attendance->date->translatedFormat('l, d F Y') }}</td></tr>
                    <tr><td class="text-muted">Check In</td><td>{{ $attendance->check_in ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Check Out</td><td>{{ $attendance->check_out ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Status</td><td>
                        @php $colors=['present'=>['bg'=>'#e8f5e9','color'=>'#2e7d32','label'=>'Hadir'],'late'=>['bg'=>'#fff8e1','color'=>'#f57f17','label'=>'Terlambat'],'absent'=>['bg'=>'#ffebee','color'=>'#c62828','label'=>'Tidak Hadir'],'leave'=>['bg'=>'#e3f2fd','color'=>'#1565c0','label'=>'Cuti']]; $s=$colors[$attendance->status]??['bg'=>'#f5f5f5','color'=>'#666','label'=>ucfirst($attendance->status)]; @endphp
                        <span class="badge rounded-pill px-3 py-1" style="background:{{ $s['bg'] }};color:{{ $s['color'] }};">{{ $s['label'] }}</span>
                    </td></tr>
                    <tr><td class="text-muted">Keterangan</td><td>{{ $attendance->notes ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Disetujui oleh</td><td>{{ $attendance->approvedBy->name ?? '—' }}</td></tr>
                </table>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('attendances.index') }}" class="btn btn-light">Kembali</a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
