@extends('layouts.app')
@section('title', 'Catat Absensi')
@section('page-title', 'Catat Absensi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                <h5 class="fw-semibold mb-0">Form Pencatatan Absensi</h5>
                <p class="text-muted small mb-0">Isi data absensi pegawai di bawah ini</p>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('attendances.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium">Pegawai <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                            <option value="">— Pilih pegawai —</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->employee_code }} — {{ $emp->name }} ({{ $emp->department->name ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                               value="{{ old('date', today()->toDateString()) }}" max="{{ today()->toDateString() }}" required>
                        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Jam Masuk</label>
                            <input type="time" name="check_in" class="form-control @error('check_in') is-invalid @enderror"
                                   value="{{ old('check_in') }}">
                            @error('check_in')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Jam Keluar</label>
                            <input type="time" name="check_out" class="form-control @error('check_out') is-invalid @enderror"
                                   value="{{ old('check_out') }}">
                            @error('check_out')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                            <option value="late"    {{ old('status') == 'late'    ? 'selected' : '' }}>Terlambat</option>
                            <option value="absent"  {{ old('status') == 'absent'  ? 'selected' : '' }}>Tidak Hadir</option>
                            <option value="leave"   {{ old('status') == 'leave'   ? 'selected' : '' }}>Cuti</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Keterangan</label>
                        <textarea name="notes" class="form-control" rows="3"
                                  placeholder="Opsional...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('attendances.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
