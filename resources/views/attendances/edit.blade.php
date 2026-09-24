@extends('layouts.app')
@section('title', 'Edit Absensi')
@section('page-title', 'Edit Absensi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-4">Edit Absensi — {{ $attendance->employee->name }}</h5>

                @if($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                @endif

                <form method="POST" action="{{ route('attendances.update', $attendance) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-medium">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" required>
                        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Jam Masuk</label>
                            <input type="time" name="check_in" class="form-control" value="{{ old('check_in', $attendance->check_in) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Jam Keluar</label>
                            <input type="time" name="check_out" class="form-control" value="{{ old('check_out', $attendance->check_out) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Hadir</option>
                            <option value="late"    {{ old('status', $attendance->status) == 'late'    ? 'selected' : '' }}>Terlambat</option>
                            <option value="absent"  {{ old('status', $attendance->status) == 'absent'  ? 'selected' : '' }}>Tidak Hadir</option>
                            <option value="leave"   {{ old('status', $attendance->status) == 'leave'   ? 'selected' : '' }}>Cuti</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Keterangan</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $attendance->notes) }}</textarea>
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
