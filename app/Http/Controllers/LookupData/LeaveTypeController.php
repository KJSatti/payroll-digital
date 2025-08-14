<?php

namespace App\Http\Controllers\LookupData;

use App\Http\Controllers\Controller;
use App\Models\LookupData\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::latest()->get();
        return view('lookup_data.leave_types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('lookup_data.leave_types.create');
    }

    public function store(Request $request)
    {
        $result = LeaveType::createLeaveType($request);

        if ($result['status']) {
            return redirect()->route('leave-types.index')->with('success', 'Leave Type created successfully.');
        }
        return back()->withErrors($result['errors'])->withInput();
    }

    public function edit($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        return view('lookup_data.leave_types.edit', compact('leaveType'));
    }

    public function update(Request $request, $id)
    {
        $result = LeaveType::updateLeaveType($request, $id);

        if ($result['status']) {
            return redirect()->route('leave-types.index')->with('success', 'Leave Type updated successfully.');
        }
        return back()->withErrors($result['errors'])->withInput();
    }

    public function destroy($id)
    {
        $result = LeaveType::deleteLeaveType($id);

        if ($result['status']) {
            return redirect()->route('leave-types.index')->with('success', 'Leave Type deleted successfully.');
        }
        return redirect()->route('leave-types.index')->with('error', $result['errors']);
    }
}