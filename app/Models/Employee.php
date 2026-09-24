<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'employee_code',   // Nomor induk pegawai
        'name',
        'email',
        'phone',
        'address',
        'position',
        'join_date',
        'status',          // 'active' | 'inactive'
        'photo',
    ];

    protected function casts(): array
    {
        return [
            'join_date' => 'date',
        ];
    }

    // Relasi ke User (akun login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Departemen
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Relasi ke Absensi
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Kehadiran hari ini
    public function todayAttendance()
    {
        return $this->hasOne(Attendance::class)
                    ->whereDate('date', today());
    }
}
