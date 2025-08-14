<?php

namespace App\Http\Controllers\LookupData;

use App\Http\Controllers\Controller;
use App\Models\LookupData\BenefitType;
use Illuminate\Http\Request;

class BenefitTypeController extends Controller
{
    public function index()
    {
        $benefitTypes = BenefitType::latest()->get();
        return view('lookup_data.benefit_types.index', compact('benefitTypes'));
    }

    public function create()
    {
        return view('lookup_data.benefit_types.create');
    }

    public function store(Request $request)
    {
        $result = BenefitType::createBenefitType($request);

        if ($result['status']) {
            return redirect()->route('benefit-types.index')->with('success', 'Benefit Type created successfully.');
        }
        return back()->withErrors($result['errors'])->withInput();
    }

    public function edit($id)
    {
        $benefitType = BenefitType::findOrFail($id);
        return view('lookup_data.benefit_types.edit', compact('benefitType'));
    }

    public function update(Request $request, $id)
    {
        $result = BenefitType::updateBenefitType($request, $id);

        if ($result['status']) {
            return redirect()->route('benefit-types.index')->with('success', 'Benefit Type updated successfully.');
        }
        return back()->withErrors($result['errors'])->withInput();
    }

    public function destroy($id)
    {
        $result = BenefitType::deleteBenefitType($id);

        if ($result['status']) {
            return redirect()->route('benefit-types.index')->with('success', 'Benefit Type deleted successfully.');
        }
        return redirect()->route('benefit-types.index')->with('error', $result['errors']);
    }
}