<?php

namespace App\Http\Controllers\LookupData;

use App\Http\Controllers\Controller;
use App\Models\LookupData\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::getDepartments();
        return view('lookup_data.departments.index', compact('departments'));
    }

    public function create()
    {
        try {
            return view('lookup_data.departments.create');
        } catch (\Throwable $e) {
            return redirect('departments')->with('error', 'Unable to load create form.');
        }
    }

    public function edit($id)
    {
        try {
            $department = Department::findOrFail($id);
            return view('lookup_data.departments.edit', compact('department'));
        } catch (\Throwable $e) {
            return redirect()->route('lookup_data.departments.index')->with('error', 'Failed to load edit page.');
        }
    }

    public function store(Request $request)
    {
        $result = Department::addDepartment($request->all());

        if ($result['success']) {
            return redirect('departments')->with('success', $result['message']);
        }

        return redirect('departments')->withErrors($result['errors']);
    }

    public function update(Request $request, $id)
    {
        $result = Department::updateDepartment($id, $request->all());

        if ($result['success']) {
            return redirect('departments')->with('success', $result['message']);
        }

        return redirect('departments')->withErrors($result['errors']);
    }

    public function destroy($id)
    {
        $result = Department::deleteDepartment($id);

        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}
