<?php

namespace App\Http\Controllers\LookupData;

use App\Http\Controllers\Controller;
use App\Models\LookupData\DeductionType;
use Illuminate\Http\Request;

class DeductionTypeController extends Controller
{
    public function index()
    {
        $benefitTypes = DeductionType::latest()->get();
        return view('lookup_data.deduction_types.index', compact('benefitTypes'));
    }

    public function create()
    {
        return view('lookup_data.deduction_types.create');
    }

    public function store(Request $request)
    {
        $result = DeductionType::createDeductionType($request);

        if ($result['status']) {
            return redirect()->route('deduction-types.index')->with('success', 'Deduction Type created successfully.');
        }
        return back()->withErrors($result['errors'])->withInput();
    }

    public function edit($id)
    {
        $benefitType = DeductionType::findOrFail($id);
        return view('lookup_data.deduction_types.edit', compact('benefitType'));
    }

    public function update(Request $request, $id)
    {
        $result = DeductionType::updateDeductionType($request, $id);

        if ($result['status']) {
            return redirect()->route('deduction-types.index')->with('success', 'Deduction Type updated successfully.');
        }
        return back()->withErrors($result['errors'])->withInput();
    }

    public function destroy($id)
    {
        $result = DeductionType::deleteDeductionType($id);

        if ($result['status']) {
            return redirect()->route('deduction-types.index')->with('success', 'Deduction Type deleted successfully.');
        }
        return redirect()->route('deduction-types.index')->with('error', $result['errors']);
    }
}