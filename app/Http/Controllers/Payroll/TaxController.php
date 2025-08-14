<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Employees\Employee;
use App\Models\Payroll\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index(Request $request)
    {
        $res = Tax::list($request);
        $employees = Employee::select('id','first_name','last_name')->get();

        $taxes = $res['data'];
        return view('taxes.index', compact('taxes','employees'))
            ->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function create()
    {
        $employees = Employee::select('id','first_name','last_name')->get();
        return view('taxes.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $res = Tax::storeFromRequest($request);

        if (!$res['status'] && isset($res['errors'])) {
            return back()->withErrors($res['errors'])->with('error', $res['message'])->withInput();
        }
        return redirect()->route('taxes.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function edit(Tax $tax)
    {
        $employees = Employee::select('id','first_name','last_name')->get();
        return view('taxes.edit', compact('tax','employees'));
    }

    public function update(Request $request, Tax $tax)
    {
        $res = $tax->updateFromRequest($request);

        if (!$res['status'] && isset($res['errors'])) {
            return back()->withErrors($res['errors'])->with('error', $res['message'])->withInput();
        }
        return redirect()->route('taxes.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function destroy(Tax $tax)
    {
        $res = $tax->deleteSafely();
        return redirect()->route('taxes.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }
}
