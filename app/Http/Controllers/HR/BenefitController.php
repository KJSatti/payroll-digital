<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\LookupData\BenefitType;
use Illuminate\Http\Request;
use App\Models\HR\Benefit;
use App\Models\Employees\Employee;

class BenefitController extends Controller
{
    public function index(Request $request)
    {
        $res = Benefit::list($request);
        $employees = Employee::select('id','first_name','last_name')->get();

        $benefits = $res['data'];
        return view('benefits.index', compact('benefits','employees'))
            ->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function create()
    {
        $employees = Employee::select('id','first_name','last_name')->get();
        $benefit_types = BenefitType::select('id','name')->get();
        return view('benefits.create', compact('employees', 'benefit_types'));
    }

    public function store(Request $request)
    {
        $res = Benefit::storeFromRequest($request);

        if (!$res['status'] && isset($res['errors'])) {
            return back()->withErrors($res['errors'])->with('error', $res['message'])->withInput();
        }
        return redirect()->route('benefits.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function edit(Benefit $benefit)
    {
        $employees = Employee::select('id','first_name','last_name')->get();
        $benefit_types = BenefitType::select('id','name')->get();
        return view('benefits.edit', compact('benefit','employees','benefit_types'));
    }

    public function update(Request $request, Benefit $benefit)
    {
        $res = $benefit->updateFromRequest($request);

        if (!$res['status'] && isset($res['errors'])) {
            return back()->withErrors($res['errors'])->with('error', $res['message'])->withInput();
        }
        return redirect()->route('benefits.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function destroy(Benefit $benefit)
    {
        $res = $benefit->deleteSafely();
        return redirect()->route('benefits.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }
}