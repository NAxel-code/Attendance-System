<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Daftar semua pegawai.
     */
    public function index(Request $request)
    {
        $query = Employee::with('department', 'user');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter departemen
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees   = $query->latest()->paginate(15)->withQueryString();
        $departments = Department::all();

        return view('employees.index', compact('employees', 'departments'));
    }

    /**
     * Form tambah pegawai.
     */
    public function create()
    {
        $departments = Department::all();
        return view('employees.create', compact('departments'));
    }

    /**
     * Simpan pegawai baru (beserta akun login).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'unique:employees,email', 'unique:users,email'],
            'employee_code' => ['required', 'string', 'max:20', 'unique:employees,employee_code'],
            'department_id' => ['required', 'exists:departments,id'],
            'position'      => ['required', 'string', 'max:100'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'address'       => ['nullable', 'string'],
            'join_date'     => ['required', 'date'],
            'status'        => ['required', 'in:active,inactive'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'photo'         => ['nullable', 'image', 'max:2048'],
        ]);

        DB::transaction(function () use ($request) {
            // Buat akun user
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'employee',
            ]);

            // Upload foto jika ada
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('employees', 'public');
            }

            // Buat data pegawai
            Employee::create([
                'user_id'       => $user->id,
                'department_id' => $request->department_id,
                'employee_code' => $request->employee_code,
                'name'          => $request->name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'address'       => $request->address,
                'position'      => $request->position,
                'join_date'     => $request->join_date,
                'status'        => $request->status,
                'photo'         => $photoPath,
            ]);
        });

        return redirect()->route('employees.index')
                         ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    /**
     * Detail pegawai.
     */
    public function show(Employee $employee)
    {
        $employee->load('department', 'user');

        // Riwayat absensi 30 hari terakhir
        $recentAttendances = $employee->attendances()
                                      ->where('date', '>=', now()->subDays(30))
                                      ->latest('date')
                                      ->get();

        return view('employees.show', compact('employee', 'recentAttendances'));
    }

    /**
     * Form edit pegawai.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update data pegawai.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', "unique:employees,email,{$employee->id}", "unique:users,email,{$employee->user_id}"],
            'employee_code' => ['required', 'string', 'max:20', "unique:employees,employee_code,{$employee->id}"],
            'department_id' => ['required', 'exists:departments,id'],
            'position'      => ['required', 'string', 'max:100'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'address'       => ['nullable', 'string'],
            'join_date'     => ['required', 'date'],
            'status'        => ['required', 'in:active,inactive'],
            'photo'         => ['nullable', 'image', 'max:2048'],
        ]);

        DB::transaction(function () use ($request, $employee) {
            // Update akun user
            $employee->user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            // Update foto jika diunggah
            $photoPath = $employee->photo;
            if ($request->hasFile('photo')) {
                if ($photoPath) {
                    Storage::disk('public')->delete($photoPath);
                }
                $photoPath = $request->file('photo')->store('employees', 'public');
            }

            $employee->update([
                'department_id' => $request->department_id,
                'employee_code' => $request->employee_code,
                'name'          => $request->name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'address'       => $request->address,
                'position'      => $request->position,
                'join_date'     => $request->join_date,
                'status'        => $request->status,
                'photo'         => $photoPath,
            ]);
        });

        return redirect()->route('employees.show', $employee)
                         ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Hapus pegawai.
     */
    public function destroy(Employee $employee)
    {
        DB::transaction(function () use ($employee) {
            // Hapus foto
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }

            // Hapus absensi terkait, lalu user
            $employee->attendances()->delete();
            $userId = $employee->user_id;
            $employee->delete();
            User::destroy($userId);
        });

        return redirect()->route('employees.index')
                         ->with('success', 'Pegawai berhasil dihapus.');
    }
}
