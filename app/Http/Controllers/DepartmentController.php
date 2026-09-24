<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('employees')->latest()->get();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'code'        => ['required', 'string', 'max:10', 'unique:departments,code'],
            'description' => ['nullable', 'string'],
        ]);

        Department::create($request->only('name', 'code', 'description'));

        return redirect()->route('departments.index')
                         ->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'code'        => ['required', 'string', 'max:10', "unique:departments,code,{$department->id}"],
            'description' => ['nullable', 'string'],
        ]);

        $department->update($request->only('name', 'code', 'description'));

        return redirect()->route('departments.index')
                         ->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Department $department)
    {
        if ($department->employees()->exists()) {
            return back()->with('error', 'Departemen masih memiliki pegawai aktif. Pindahkan pegawai terlebih dahulu.');
        }

        $department->delete();

        return redirect()->route('departments.index')
                         ->with('success', 'Departemen berhasil dihapus.');
    }
}
