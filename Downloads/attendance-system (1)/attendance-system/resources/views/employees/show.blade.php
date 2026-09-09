@extends('layouts.app')
@section('title', 'Detail Pegawai')
@section('page-title', 'Detail Pegawai')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 text-center">
            <div class="card-body p-4">
                @if($employee->photo)
                    <img src="{{ asset('storage/'.$employee->photo) }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit:cover;">
                @else
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold mx-auto mb-3" style="width:100px;height:100px;font-size:2rem;">
                        {{ strtoupper(substr($employee->name, 0, 2)) }}
                    </div>
                @endif
                <h5 class="fw-bold mb-1">{{ $employee->name }}</h5>
                <p class="text-muted mb-2">{{ $employee->position }}</p>
                <span class="badge {{ $employee->status === 'active' ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3">
                    {{ $employee->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                </span>
                <hr>
                <div class="text-start">
                    <div class="mb-2"><span class="text-muted small">Kode:</span><div class="fw-medium">{{ $employee->employee_code }}</div></div>
                    <div class="mb-2"><span class="text-muted small">Email:</span><div class="fw-medium">{{ $employee->email }}</div></div>
                    <div class="mb-2"><span class="text-muted small">Telepon:</span><div class="fw-medium">{{ $employee->phone ?? '—' }}</div></div>
                    <div class="mb-2"><span class="text-muted small">Departemen:</span><div class="fw-medium">{{ $employee->department->name ?? '—' }}</div></div>
                    <div class="mb-2"><span class="text-muted small">Bergabung:</span><div class="fw-medium">{{ $employee->join_date->format('d M Y') }}</div></div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary btn-sm w-100"><i class="bi bi-pencil me-1"></i>Edit</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h6 class="fw-semibold">Riwayat Absensi (30 hari terakhir)</h6>
            </div>
            <div class="card-body p-0 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th class="ps-4">Tanggal</th><th>Check In</th><th>Check Out</th><th class="pe-4">Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentAttendances as $att)
                            <tr>
                                <td class="ps-4">{{ $att->date->translatedFormat('d M Y') }}</td>
                                <td>{{ $att->check_in ?? '—' }}</td>
                                <td>{{ $att->check_out ?? '—' }}</td>
                                <td class="pe-4">
                                    @php $colors=['present'=>['bg'=>'#e8f5e9','color'=>'#2e7d32','label'=>'Hadir'],'late'=>['bg'=>'#fff8e1','color'=>'#f57f17','label'=>'Terlambat'],'absent'=>['bg'=>'#ffebee','color'=>'#c62828','label'=>'Tidak Hadir'],'leave'=>['bg'=>'#e3f2fd','color'=>'#1565c0','label'=>'Cuti']]; $s=$colors[$att->status]??['bg'=>'#f5f5f5','color'=>'#666','label'=>ucfirst($att->status)]; @endphp
                                    <span class="badge rounded-pill px-3 py-1" style="background:{{ $s['bg'] }};color:{{ $s['color'] }};font-size:.78rem;">{{ $s['label'] }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat absensi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
