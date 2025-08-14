<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Attendence\Attendence;
use App\Models\Employees\Employee;
use Illuminate\Http\Request;

class AttendenceController extends Controller
{
    public function index(Request $request)
    {
        $res = Attendence::list($request);
        $employees = Employee::select('id','first_name','last_name')->get();

        $attendences = $res['data'];
        return view('attendences.index', compact('attendences','employees'))
            ->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function create()
    {
        $employees = Employee::select('id','first_name','last_name')->get();
        return view('attendences.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $res = Attendence::storeFromRequest($request);

        if (!$res['status'] && isset($res['errors'])) {
            return back()->withErrors($res['errors'])->with('error', $res['message'])->withInput();
        }
        return redirect()->route('attendences.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function edit(Attendence $attendence)
    {
        $employees = Employee::select('id','first_name','last_name')->get();
        return view('attendences.edit', compact('attendence','employees'));
    }

    public function update(Request $request, Attendence $attendence)
    {
        $res = $attendence->updateFromRequest($request);

        if (!$res['status'] && isset($res['errors'])) {
            return back()->withErrors($res['errors'])->with('error', $res['message'])->withInput();
        }
        return redirect()->route('attendences.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function destroy(Attendence $attendence)
    {
        $res = $attendence->deleteSafely();
        return redirect()->route('attendences.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }
}