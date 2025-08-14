<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Models\Employees\Employee;
use App\Models\LookupData\Department;
use App\Models\LookupData\Position;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request) {
        $employees = Employee::getAllEmployees();
        $departments = Department::getDepartments();
        $positions = Position::getPositions();
        return view('employees.index', compact('employees', 'departments', 'positions'));
    }

    public function create(Request $request) {
        $departments = Department::getDepartments();
        $positions = Position::getPositions();
        return view('employees.create', compact('departments', 'positions'));
    }

    public function edit(Request $request, $id) {
        $departments = Department::getDepartments();
        $positions = Position::getPositions();
        $employee = Employee::find($id);
        return view('employees.edit', compact('employee', 'departments', 'positions'));
    }
    public function store(Request $request)
    {
        $result = Employee::addEmployee($request);
        return redirect()->route('employees.index')->with($result['status'] ? 'success' : 'error', $result['message']);
    }

    public function update(Request $request, $id)
    {
        $result = Employee::updateEmployee($request, $id);
        return redirect()->route('employees.index')->with($result['status'] ? 'success' : 'error', $result['message']);
    }

    public function destroy($id)
    {
        $result = Employee::deleteEmployee($id);
        return redirect()->route('employees.index')->with($result['status'] ? 'success' : 'error', $result['message']);
    }
}
