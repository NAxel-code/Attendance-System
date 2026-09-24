<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Daftar absensi (admin: semua pegawai, pegawai: milik sendiri).
     */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Attendance::with('employee.department');

        // Pegawai biasa hanya melihat absensinya sendiri
        if (! $user->isAdmin()) {
            $query->where('employee_id', $user->employee?->id);
        }

        // Filter bulan & tahun
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year',  now()->year);
        $query->byMonth($month, $year);

        // Filter departemen (admin only)
        if ($user->isAdmin() && $request->filled('department_id')) {
            $query->byDepartment($request->department_id);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->latest('date')->paginate(20)->withQueryString();
        $departments = Department::all();

        return view('attendances.index', compact('attendances', 'departments', 'month', 'year'));
    }

    /**
     * Form tambah absensi (admin).
     */
    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
        return view('attendances.create', compact('employees'));
    }

    /**
     * Simpan absensi baru.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'date'        => ['required', 'date', 'before_or_equal:today'],
            'check_in'    => ['nullable', 'date_format:H:i'],
            'check_out'   => ['nullable', 'date_format:H:i', 'after:check_in'],
            'status'      => ['required', 'in:present,late,absent,leave'],
            'notes'       => ['nullable', 'string', 'max:500'],
        ]);

        // Cek duplikat
        $exists = Attendance::where('employee_id', $request->employee_id)
                             ->whereDate('date', $request->date)
                             ->exists();

        if ($exists) {
            return back()->withErrors(['date' => 'Absensi untuk pegawai ini pada tanggal tersebut sudah ada.']);
        }

        Attendance::create([
            'employee_id' => $request->employee_id,
            'date'        => $request->date,
            'check_in'    => $request->check_in,
            'check_out'   => $request->check_out,
            'status'      => $request->status,
            'notes'       => $request->notes,
            'approved_by' => $user->isAdmin() ? $user->id : null,
        ]);

        return redirect()->route('attendances.index')
                         ->with('success', 'Absensi berhasil dicatat.');
    }

    /**
     * Detail absensi.
     */
    public function show(Attendance $attendance)
    {
        $this->authorizeAccess($attendance);
        $attendance->load('employee.department', 'approvedBy');
        return view('attendances.show', compact('attendance'));
    }

    /**
     * Form edit absensi (admin only).
     */
    public function edit(Attendance $attendance)
    {
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
        return view('attendances.edit', compact('attendance', 'employees'));
    }

    /**
     * Update absensi.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'date'      => ['required', 'date'],
            'check_in'  => ['nullable', 'date_format:H:i'],
            'check_out' => ['nullable', 'date_format:H:i', 'after:check_in'],
            'status'    => ['required', 'in:present,late,absent,leave'],
            'notes'     => ['nullable', 'string', 'max:500'],
        ]);

        $attendance->update([
            'date'        => $request->date,
            'check_in'    => $request->check_in,
            'check_out'   => $request->check_out,
            'status'      => $request->status,
            'notes'       => $request->notes,
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('attendances.show', $attendance)
                         ->with('success', 'Absensi berhasil diperbarui.');
    }

    /**
     * Hapus absensi.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendances.index')
                         ->with('success', 'Data absensi berhasil dihapus.');
    }

    /**
     * Check-in mandiri oleh pegawai.
     */
    public function checkIn(Request $request)
    {
        $user     = Auth::user();
        $employee = $user->employee;

        if (! $employee) {
            return back()->with('error', 'Data pegawai tidak ditemukan.');
        }

        // Cek sudah check-in hari ini?
        $today = Attendance::where('employee_id', $employee->id)
                            ->whereDate('date', today())
                            ->first();

        if ($today) {
            return back()->with('error', 'Anda sudah melakukan check-in hari ini.');
        }

        // Tentukan status: terlambat jika setelah jam 08:30
        $checkInTime = now();
        $status      = $checkInTime->hour > 8 || ($checkInTime->hour === 8 && $checkInTime->minute > 30)
                       ? 'late'
                       : 'present';

        Attendance::create([
            'employee_id' => $employee->id,
            'date'        => today(),
            'check_in'    => $checkInTime->format('H:i'),
            'status'      => $status,
        ]);

        return back()->with('success', 'Check-in berhasil pada ' . $checkInTime->format('H:i') . '.');
    }

    /**
     * Check-out mandiri oleh pegawai.
     */
    public function checkOut(Request $request)
    {
        $user     = Auth::user();
        $employee = $user->employee;

        $today = Attendance::where('employee_id', $employee->id)
                            ->whereDate('date', today())
                            ->first();

        if (! $today) {
            return back()->with('error', 'Anda belum melakukan check-in hari ini.');
        }

        if ($today->check_out) {
            return back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        $today->update(['check_out' => now()->format('H:i')]);

        return back()->with('success', 'Check-out berhasil pada ' . now()->format('H:i') . '.');
    }

    /**
     * Pastikan pegawai hanya akses absensinya sendiri.
     */
    private function authorizeAccess(Attendance $attendance): void
    {
        $user = Auth::user();
        if (! $user->isAdmin() && $attendance->employee_id !== $user->employee?->id) {
            abort(403);
        }
    }
}
