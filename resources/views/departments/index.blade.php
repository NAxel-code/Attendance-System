@extends('layouts.app')
@section('title', 'Data Departemen')
@section('page-title', 'Data Departemen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Total {{ $departments->count() }} departemen</p>
    <a href="{{ route('departments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Departemen</a>
</div>
<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th class="ps-4">#</th><th>Kode</th><th>Nama Departemen</th><th>Deskripsi</th><th>Jumlah Pegawai</th><th class="text-end pe-4">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                    <tr>
                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                        <td><span class="badge bg-primary rounded-pill px-3">{{ $dept->code }}</span></td>
                        <td class="fw-medium">{{ $dept->name }}</td>
                        <td class="text-muted small">{{ $dept->description ?? '—' }}</td>
                        <td><span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">{{ $dept->employees_count }} pegawai</span></td>
                        <td class="text-end pe-4">
                            <a href="{{ route('departments.edit', $dept) }}" class="btn btn-sm btn-outline-primary py-0"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('departments.destroy', $dept) }}" class="d-inline" onsubmit="return confirm('Hapus departemen {{ $dept->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-0 ms-1"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-diagram-3 fs-3 d-block mb-2"></i>Belum ada departemen.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
