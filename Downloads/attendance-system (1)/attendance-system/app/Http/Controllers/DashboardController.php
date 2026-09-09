<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik untuk admin
        if ($user->isAdmin()) {
            $stats = [
                'total_employees'  => Employee::where('status', 'active')->count(),
                'total_departments'=> Department::count(),
                'present_today'    => Attendance::whereDate('date', today())
                                                ->where('status', 'present')->count(),
                'absent_today'     => Attendance::whereDate('date', today())
                                                ->where('status', 'absent')->count(),
                'late_today'       => Attendance::whereDate('date', today())
                                                ->where('status', 'late')->count(),
            ];

            // 10 absensi terbaru
            $recentAttendances = Attendance::with('employee.department')
                                           ->latest('date')
                                           ->take(10)
                                           ->get();

            return view('dashboard.admin', compact('stats', 'recentAttendances'));
        }

        // Dashboard untuk pegawai biasa
        $employee      = $user->employee;
        $todayRecord   = $employee?->todayAttendance;
        $monthlyStats  = [];

        if ($employee) {
            $monthlyStats = [
                'present' => Attendance::where('employee_id', $employee->id)
                                       ->byMonth(now()->month, now()->year)
                                       ->where('status', 'present')->count(),
                'late'    => Attendance::where('employee_id', $employee->id)
                                       ->byMonth(now()->month, now()->year)
                                       ->where('status', 'late')->count(),
                'absent'  => Attendance::where('employee_id', $employee->id)
                                       ->byMonth(now()->month, now()->year)
                                       ->where('status', 'absent')->count(),
                'leave'   => Attendance::where('employee_id', $employee->id)
                                       ->byMonth(now()->month, now()->year)
                                       ->where('status', 'leave')->count(),
            ];
        }

        return view('dashboard.employee', compact('employee', 'todayRecord', 'monthlyStats'));
    }
}
