<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Employees\Employee;
use App\Models\LookupData\PaymentMethod;
use App\Models\Payroll\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 15);
        $res = Payment::list($request, $perPage);

        $employees = Employee::select('id', 'first_name', 'last_name')->get();

        $payments = $res['data']; // paginator
        $methods = PaymentMethod::get();
        return view('payments.index', compact('payments', 'employees', 'methods'))
            ->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function create()
    {
        $employees = Employee::select('id', 'first_name', 'last_name')->get();
        $methods = PaymentMethod::get();
        return view('payments.create', compact('employees', 'methods'));
    }

    public function store(Request $request)
    {
        $res = Payment::storeFromRequest($request);

        if (!$res['status'] && isset($res['errors'])) {
            return back()->withErrors($res['errors'])->with('error', $res['message'])->withInput();
        }
        return redirect()->route('payments.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function edit(Payment $payment)
    {
        $employees = Employee::select('id', 'first_name', 'last_name')->get();
        $methods = PaymentMethod::get();
        return view('payments.edit', compact('payment', 'employees', 'methods'));
    }

    public function update(Request $request, Payment $payment)
    {
        $res = $payment->updateFromRequest($request);

        if (!$res['status'] && isset($res['errors'])) {
            return back()->withErrors($res['errors'])->with('error', $res['message'])->withInput();
        }
        return redirect()->route('payments.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }

    public function destroy(Payment $payment)
    {
        $res = $payment->deleteSafely();
        return redirect()->route('payments.index')->with($res['status'] ? 'success' : 'error', $res['message']);
    }
}
