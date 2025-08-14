<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Employees\Employee;
use App\Models\Payroll\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $salaries = Salary::getAll($request);
        $employees = Employee::all();
        return view('salaries.index', compact('salaries', 'employees'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('salaries.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $response = Salary::storeData($request);
        return redirect()->route('salaries.index')->with($response['status'] ? 'success' : 'error', $response['message']);
    }

    public function edit(Salary $salary)
    {
        $employees = Employee::all();
        return view('salaries.edit', compact('salary', 'employees'));
    }

    public function update(Request $request, Salary $salary)
    {
        $response = $salary->updateData($request);
        return redirect()->route('salaries.index')->with($response['status'] ? 'success' : 'error', $response['message']);
    }

    public function destroy(Salary $salary)
    {
        $response = $salary->deleteData();
        return redirect()->route('salaries.index')->with($response['status'] ? 'success' : 'error', $response['message']);
    }
}