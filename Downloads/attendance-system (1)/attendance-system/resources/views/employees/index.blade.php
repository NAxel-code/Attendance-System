@extends('layouts.app')
@section('title', 'Data Pegawai')
@section('page-title', 'Data Pegawai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Total {{ $employees->total() }} pegawai terdaftar</p>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i>Tambah Pegawai
    </a>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('employees.index') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-medium">Cari Pegawai</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nama, kode, atau email..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-medium">Departemen</label>
                <select name="department_id" class="form-select">
                    <option value="">Semua</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('employees.index') }}" class="btn btn-light ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Nama Pegawai</th>
                        <th>Departemen</th>
                        <th>Jabatan</th>
                        <th>Bergabung</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                    <tr>
                        <td class="ps-4 text-muted small fw-medium">{{ $emp->employee_code }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($emp->photo)
                                    <img src="{{ asset('storage/'.$emp->photo) }}" class="rounded-circle" width="36" height="36" style="object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                                         style="width:36px;height:36px;font-size:.8rem;">
                                        {{ strtoupper(substr($emp->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-medium">{{ $emp->name }}</div>
                                    <div class="text-muted small">{{ $emp->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">{{ $emp->department->name ?? '—' }}</td>
                        <td>{{ $emp->position }}</td>
                        <td class="text-muted small">{{ $emp->join_date->format('d M Y') }}</td>
                        <td>
                            @if($emp->status === 'active')
                                <span class="badge bg-success-subtle text-success rounded-pill px-3">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('employees.show', $emp) }}" class="btn btn-sm btn-outline-secondary py-0">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('employees.edit', $emp) }}" class="btn btn-sm btn-outline-primary py-0 ms-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('employees.destroy', $emp) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus pegawai {{ $emp->name }}? Semua data absensinya juga akan dihapus.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-0 ms-1"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-3 d-block mb-2"></i>
                            Tidak ada pegawai ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($employees->hasPages())
    <div class="card-footer bg-transparent">
        {{ $employees->links() }}
    </div>
    @endif
</div>
@endsection
