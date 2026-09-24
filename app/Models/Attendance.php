<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',        // Jam masuk
        'check_out',       // Jam keluar
        'status',          // 'present' | 'late' | 'absent' | 'leave'
        'notes',
        'approved_by',     // user_id admin yang menyetujui
    ];

    protected function casts(): array
    {
        return [
            'date'      => 'date',
            'check_in'  => 'datetime:H:i',
            'check_out' => 'datetime:H:i',
        ];
    }

    // Relasi ke Pegawai
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Relasi ke Admin yang approve
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Hitung durasi kerja (dalam menit)
    public function getWorkDurationAttribute(): ?int
    {
        if ($this->check_in && $this->check_out) {
            return $this->check_in->diffInMinutes($this->check_out);
        }
        return null;
    }

    // Label status dalam bahasa Indonesia
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'present' => 'Hadir',
            'late'    => 'Terlambat',
            'absent'  => 'Tidak Hadir',
            'leave'   => 'Cuti',
            default   => ucfirst($this->status),
        };
    }

    // Warna badge status
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'present' => 'success',
            'late'    => 'warning',
            'absent'  => 'danger',
            'leave'   => 'info',
            default   => 'secondary',
        };
    }

    // Scope filter bulan & tahun
    public function scopeByMonth($query, int $month, int $year)
    {
        return $query->whereMonth('date', $month)->whereYear('date', $year);
    }

    // Scope filter berdasarkan departemen
    public function scopeByDepartment($query, int $departmentId)
    {
        return $query->whereHas('employee', function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        });
    }
}
