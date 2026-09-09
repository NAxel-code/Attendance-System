<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Laporan rekap absensi bulanan.
     */
    public function monthly(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year',  now()->year);

        $query = Employee::with([
            'department',
            'attendances' => fn ($q) => $q->byMonth($month, $year),
        ])->where('status', 'active');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $employees   = $query->orderBy('name')->get();
        $departments = Department::all();

        // Ringkasan total per status
        $summary = [
            'present' => Attendance::byMonth($month, $year)->where('status', 'present')->count(),
            'late'    => Attendance::byMonth($month, $year)->where('status', 'late')->count(),
            'absent'  => Attendance::byMonth($month, $year)->where('status', 'absent')->count(),
            'leave'   => Attendance::byMonth($month, $year)->where('status', 'leave')->count(),
        ];

        return view('reports.monthly', compact('employees', 'departments', 'summary', 'month', 'year'));
    }

    /**
     * Laporan rekap per departemen.
     */
    public function byDepartment(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year',  now()->year);

        $departments = Department::with('employees')->get()->map(function ($dept) use ($month, $year) {
            $employeeIds = $dept->employees->pluck('id');

            $dept->stats = [
                'total_employees' => $dept->employees->count(),
                'present'         => Attendance::whereIn('employee_id', $employeeIds)->byMonth($month, $year)->where('status', 'present')->count(),
                'late'            => Attendance::whereIn('employee_id', $employeeIds)->byMonth($month, $year)->where('status', 'late')->count(),
                'absent'          => Attendance::whereIn('employee_id', $employeeIds)->byMonth($month, $year)->where('status', 'absent')->count(),
                'leave'           => Attendance::whereIn('employee_id', $employeeIds)->byMonth($month, $year)->where('status', 'leave')->count(),
            ];

            return $dept;
        });

        return view('reports.department', compact('departments', 'month', 'year'));
    }
}
