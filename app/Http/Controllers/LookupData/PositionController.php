<?php

namespace App\Http\Controllers\LookupData;

use App\Http\Controllers\Controller;
use App\Models\LookupData\Department;
use App\Models\LookupData\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::getPositions();
        return view('lookup_data.positions.index', compact('positions'));
    }

    public function create()
    {
        try {
            $departments = Department::getDepartments();
            return view('lookup_data.positions.create', compact('departments'));
        } catch (\Throwable $e) {
            return redirect('positions')->with('error', 'Unable to load create form.');
        }
    }

    public function edit($id)
    {
        try {
            $departments = Department::getDepartments();
            $position = Position::findOrFail($id);
            return view('lookup_data.positions.edit', compact('departments', 'position'));
        } catch (\Throwable $e) {
            return redirect()->route('lookup_data.positions.index')->with('error', 'Failed to load edit page.');
        }
    }

    public function store(Request $request)
    {
        $result = Position::addPosition($request->all());

        if ($result['success']) {
            return redirect('positions')->with('success', $result['message']);
        }

        return redirect('positions')->withErrors($result['errors']);
    }

    public function update(Request $request, $id)
    {
        $result = Position::updatePosition($id, $request->all());

        if ($result['success']) {
            return redirect('positions')->with('success', $result['message']);
        }

        return redirect('positions')->withErrors($result['errors']);
    }

    public function destroy($id)
    {
        $result = Position::deletePosition($id);

        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}