<?php

namespace App\Http\Controllers\LookupData;

use App\Http\Controllers\Controller;
use App\Models\LookupData\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->get();
        return view('lookup_data.payment_methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('lookup_data.payment_methods.create');
    }

    public function store(Request $request)
    {
        $result = PaymentMethod::createPaymentMethod($request);

        if ($result['status']) {
            return redirect()->route('payment-methods.index')->with('success', 'Payment Method created successfully.');
        }
        return back()->withErrors($result['errors'])->withInput();
    }

    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        return view('lookup_data.payment_methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, $id)
    {
        $result = PaymentMethod::updatePaymentMethod($request, $id);

        if ($result['status']) {
            return redirect()->route('payment-methods.index')->with('success', 'Payment Method updated successfully.');
        }
        return back()->withErrors($result['errors'])->withInput();
    }

    public function destroy($id)
    {
        $result = PaymentMethod::deletePaymentMethod($id);

        if ($result['status']) {
            return redirect()->route('payment-methods.index')->with('success', 'Payment Method deleted successfully.');
        }
        return redirect()->route('payment-methods.index')->with('error', $result['errors']);
    }
}