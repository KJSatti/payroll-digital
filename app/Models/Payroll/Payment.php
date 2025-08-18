<?php

namespace App\Models\Payroll;

use App\Models\Employees\Employee;
use App\Models\LookupData\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'gross_pay',
        'total_deductions',
        'net_pay',
        'payment_date',
        'payment_method',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /* ---------- Relationships ---------- */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }

    /* ---------- Validation ---------- */
    protected static function rules(): array
    {
        return [
            'employee_id'      => 'required|exists:employees,id',
            'gross_pay'        => 'required|numeric|min:0',
            'total_deductions' => 'required|numeric|min:0',
            'net_pay'          => 'required|numeric|min:0',
            'payment_date'     => 'required|date',
            'payment_method'   => 'required|string|max:100',
        ];
    }

    protected static function messages(): array
    {
        return [
            'employee_id.required' => 'Employee is required.',
            'employee_id.exists'   => 'Selected employee does not exist.',
            'gross_pay.required'   => 'Gross pay is required.',
            'total_deductions.required' => 'Total deductions are required.',
            'net_pay.required'     => 'Net pay is required.',
            'payment_date.required'=> 'Payment date is required.',
            'payment_method.required' => 'Payment method is required.',
        ];
    }

    protected static function validate(array $data)
    {
        return Validator::make($data, self::rules(), self::messages());
    }

    /* ---------- Listing (with filters + pagination) ---------- */
    public static function list($request = null, int $perPage = 15): array
    {
        try {
            $q = self::with('employee')->latest('payment_date');

            if ($request) {
                if ($request->filled('employee_id')) {
                    $q->where('employee_id', $request->employee_id);
                }
                if ($request->filled('payment_method')) {
                    // case-insensitive match
                    $q->whereRaw('LOWER(payment_method) = ?', [strtolower($request->payment_method)]);
                }
                if ($request->filled('date_from')) {
                    $q->whereDate('payment_date', '>=', $request->date_from);
                }
                if ($request->filled('date_to')) {
                    $q->whereDate('payment_date', '<=', $request->date_to);
                }
            }

            return [
                'status'  => true,
                'message' => 'Payments fetched successfully.',
                'data'    => $q->paginate($perPage)->withQueryString(),
            ];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to fetch payments.', 'data' => collect()];
        }
    }

    /* ---------- CRUD (with try/catch) ---------- */
    public static function storeFromRequest($request): array
    {
        try {
            $payload = $request->only([
                'employee_id',
                'gross_pay',
                'total_deductions',
                'net_pay',
                'payment_date',
                'payment_method',
            ]);

            $validator = self::validate($payload);
            if ($validator->fails()) {
                return ['status' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()];
            }

            $payload['created_by'] = auth()->id();
            self::create($payload);

            return ['status' => true, 'message' => 'Payment added successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to add payment.'];
        }
    }

    public function updateFromRequest($request): array
    {
        try {
            $payload = $request->only([
                'employee_id',
                'gross_pay',
                'total_deductions',
                'net_pay',
                'payment_date',
                'payment_method',
            ]);

            $validator = self::validate($payload);
            if ($validator->fails()) {
                return ['status' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()];
            }

            $payload['updated_by'] = auth()->id();
            $this->update($payload);

            return ['status' => true, 'message' => 'Payment updated successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to update payment.'];
        }
    }

    public function deleteSafely(): array
    {
        try {
            $this->update(['deleted_by' => auth()->id()]);
            $this->delete();
            return ['status' => true, 'message' => 'Payment deleted successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to delete payment.'];
        }
    }
}