<?php

namespace App\Models\HR;

use App\Models\Employees\Employee;
use App\Models\LookupData\DeductionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;

class Deduction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'deduction_type_id',
        'deduction_amount',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /* ================= Relations ================= */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Optional (if you have a deduction_types table)
    public function deductionType()
    {
        return $this->belongsTo(DeductionType::class, 'deduction_type_id');
    }

    /* ================= Validation ================= */
    protected static function rules(bool $isUpdate = false): array
    {
        $existsType = class_exists(\App\Models\DeductionType::class) ? '|exists:deduction_types,id' : '';
        return [
            'employee_id'       => 'required|exists:employees,id',
            'deduction_type_id' => 'nullable|integer' . $existsType,
            'deduction_amount'  => 'required|numeric|min:0',
            'start_date'        => 'required|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
        ];
    }

    protected static function messages(): array
    {
        return [
            'employee_id.required'      => 'Employee is required.',
            'employee_id.exists'        => 'Selected employee does not exist.',
            'deduction_amount.required' => 'Deduction amount is required.',
            'deduction_amount.numeric'  => 'Deduction amount must be numeric.',
            'deduction_amount.min'      => 'Deduction amount cannot be negative.',
            'start_date.required'       => 'Start date is required.',
            'end_date.after_or_equal'   => 'End date must be after or equal to the start date.',
        ];
    }

    protected static function validate(array $data)
    {
        return Validator::make($data, self::rules(), self::messages());
    }

    /* ================= Queries (with filters) ================= */
    public static function list($request = null): array
    {
        try {
            $q = self::with(['employee', 'deductionType'])->latest('start_date');

            if ($request) {
                if ($request->filled('employee_id')) {
                    $q->where('employee_id', $request->employee_id);
                }
                if ($request->filled('deduction_type_id')) {
                    $q->where('deduction_type_id', $request->deduction_type_id);
                }
                if ($request->filled('date_from')) {
                    $q->whereDate('start_date', '>=', $request->date_from);
                }
                if ($request->filled('date_to')) {
                    $q->whereDate('start_date', '<=', $request->date_to);
                }
            }

            return ['status' => true, 'message' => 'Deductions fetched successfully.', 'data' => $q->get()];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to fetch deductions.', 'data' => collect()];
        }
    }

    /* ================= CRUD ================= */
    public static function storeFromRequest($request): array
    {
        try {
            $payload = $request->only([
                'employee_id',
                'deduction_type_id',
                'deduction_amount',
                'start_date',
                'end_date',
            ]);

            $validator = self::validate($payload);
            if ($validator->fails()) {
                return ['status' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()];
            }

            $payload['created_by'] = auth()->id();
            self::create($payload);

            return ['status' => true, 'message' => 'Deduction added successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to add deduction.'];
        }
    }

    public function updateFromRequest($request): array
    {
        try {
            $payload = $request->only([
                'employee_id',
                'deduction_type_id',
                'deduction_amount',
                'start_date',
                'end_date',
            ]);

            $validator = self::validate($payload);
            if ($validator->fails()) {
                return ['status' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()];
            }

            $payload['updated_by'] = auth()->id();
            $this->update($payload);

            return ['status' => true, 'message' => 'Deduction updated successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to update deduction.'];
        }
    }

    public function deleteSafely(): array
    {
        try {
            $this->update(['deleted_by' => auth()->id()]);
            $this->delete();
            return ['status' => true, 'message' => 'Deduction deleted successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to delete deduction.'];
        }
    }
}