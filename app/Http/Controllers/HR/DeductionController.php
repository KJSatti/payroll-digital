<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Deduction;
use App\Models\Employees\Employee;
use App\Models\LookupData\DeductionType; 
use Illuminate\Http\Request;

class DeductionController extends Controller
{
    public function index(Request $request)
    {
        $res = Deduction::list($request);
        $employees = Employee::select('id','first_name','last_name')->get();
        $deduction_types = class_exists(DeductionType::class)
            ? DeductionType::select('id','name')->get()
            : collect();

        $deductions = $res['data'];

        return view('deductions.index', compact('deductions','employees','deduction_types'))
            ->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function create()
    {
        $employees = Employee::select('id','first_name','last_name')->get();
        $deduction_types = class_exists(DeductionType::class)
            ? DeductionType::select('id','name')->get()
            : collect();

        return view('deductions.create', compact('employees','deduction_types'));
    }

    public function store(Request $request)
    {
        $res = Deduction::storeFromRequest($request);

        if (!$res['status'] && isset($res['errors'])) {
            return back()->withErrors($res['errors'])->with('error', $res['message'])->withInput();
        }
        return redirect()->route('deductions.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function edit(Deduction $deduction)
    {
        $employees = Employee::select('id','first_name','last_name')->get();
        $deduction_types = class_exists(DeductionType::class)
            ? DeductionType::select('id','name')->get()
            : collect();

        return view('deductions.edit', compact('deduction','employees','deduction_types'));
    }

    public function update(Request $request, Deduction $deduction)
    {
        $res = $deduction->updateFromRequest($request);

        if (!$res['status'] && isset($res['errors'])) {
            return back()->withErrors($res['errors'])->with('error', $res['message'])->withInput();
        }
        return redirect()->route('deductions.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function destroy(Deduction $deduction)
    {
        $res = $deduction->deleteSafely();
        return redirect()->route('deductions.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }
}