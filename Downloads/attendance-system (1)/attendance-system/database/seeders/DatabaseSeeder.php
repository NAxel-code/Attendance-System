<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin utama
        $admin = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@perusahaan.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Departemen contoh
        $departments = Department::insert([
            ['name' => 'IT & Teknologi',   'code' => 'IT',  'description' => 'Divisi Teknologi Informasi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keuangan',          'code' => 'FIN', 'description' => 'Divisi Keuangan & Akuntansi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sumber Daya Manusia','code' => 'HR', 'description' => 'Divisi Human Resources', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Operasional',       'code' => 'OPS', 'description' => 'Divisi Operasional', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $itDept  = Department::where('code', 'IT')->first();
        $hrDept  = Department::where('code', 'HR')->first();

        // Pegawai contoh
        $userBudi = User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@perusahaan.com',
            'password' => Hash::make('password'),
            'role'     => 'employee',
        ]);

        Employee::create([
            'user_id'       => $userBudi->id,
            'department_id' => $itDept->id,
            'employee_code' => 'EMP-001',
            'name'          => 'Budi Santoso',
            'email'         => 'budi@perusahaan.com',
            'phone'         => '081234567890',
            'position'      => 'Backend Developer',
            'join_date'     => '2022-03-01',
            'status'        => 'active',
        ]);

        $userSari = User::create([
            'name'     => 'Sari Dewi',
            'email'    => 'sari@perusahaan.com',
            'password' => Hash::make('password'),
            'role'     => 'employee',
        ]);

        Employee::create([
            'user_id'       => $userSari->id,
            'department_id' => $hrDept->id,
            'employee_code' => 'EMP-002',
            'name'          => 'Sari Dewi',
            'email'         => 'sari@perusahaan.com',
            'phone'         => '082345678901',
            'position'      => 'HR Officer',
            'join_date'     => '2023-01-15',
            'status'        => 'active',
        ]);
    }
}
