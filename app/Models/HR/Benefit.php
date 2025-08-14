<?php

namespace App\Models\HR;

use App\Models\Employees\Employee;
use App\Models\LookupData\BenefitType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Benefit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'benefit_type_id',
        'enrollment_date',
        'monthly_contribution',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /* ============== Relationships ============== */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function benefitType()
    {
        return $this->hasOne(BenefitType::class, 'id', 'benefit_type_id');

    }

    /* ============== Validation ============== */
    protected static function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'benefit_type_id' => 'required',
            'enrollment_date' => 'required|date',
            'monthly_contribution' => 'required|numeric|min:0',
        ];
    }

    protected static function messages(): array
    {
        return [
            'employee_id.required' => 'Employee is required.',
            'employee_id.exists' => 'Selected employee does not exist.',
            'benefit_type.required' => 'Benefit type is required.',
            'benefit_type_id.string' => 'Benefit type must be text.',
            'enrollment_date.required' => 'Enrollment date is required.',
            'enrollment_date.date' => 'Enrollment date must be a valid date.',
            'monthly_contribution.required' => 'Monthly contribution is required.',
            'monthly_contribution.numeric' => 'Monthly contribution must be a number.',
            'monthly_contribution.min' => 'Monthly contribution cannot be negative.',
        ];
    }

    protected static function validate(array $data)
    {
        return Validator::make($data, self::rules(), self::messages());
    }

    /* ============== Queries (List with filters) ============== */
    public static function list($request = null): array
    {
        try {
            $q = self::with(['employee', 'benefitType'])->latest('enrollment_date');

            if ($request) {
                if ($request->filled('employee_id')) {
                    $q->where('employee_id', $request->employee_id);
                }
                if ($request->filled('benefit_type')) {
                    $q->where('benefit_type', 'like', '%' . trim($request->benefit_type) . '%');
                }
                if ($request->filled('date_from')) {
                    $q->whereDate('enrollment_date', '>=', $request->date_from);
                }
                if ($request->filled('date_to')) {
                    $q->whereDate('enrollment_date', '<=', $request->date_to);
                }
            }
            return [
                'status' => true,
                'message' => 'Benefits fetched successfully.',
                'data' => $q->orderBy('id', 'desc')->get(),
            ];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to fetch benefits.', 'data' => collect()];
        }
    }

    /* ============== CRUD (with try/catch) ============== */
    public static function storeFromRequest($request): array
    {
        try {
            $payload = $request->only([
                'employee_id',
                'benefit_type_id',
                'enrollment_date',
                'monthly_contribution',
            ]);

            $validator = self::validate($payload);
            if ($validator->fails()) {
                return ['status' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()];
            }

            $payload['created_by'] = auth()->id();
            self::create($payload);

            return ['status' => true, 'message' => 'Benefit added successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to add benefit.'];
        }
    }

    public function updateFromRequest($request): array
    {
        try {
            $payload = $request->only([
                'employee_id',
                'benefit_type_id',
                'enrollment_date',
                'monthly_contribution',
            ]);

            $validator = self::validate($payload);
            if ($validator->fails()) {
                return ['status' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()];
            }

            $payload['updated_by'] = auth()->id();
            $this->update($payload);

            return ['status' => true, 'message' => 'Benefit updated successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to update benefit.'];
        }
    }

    public function deleteSafely(): array
    {
        try {
            $this->update(['deleted_by' => auth()->id()]);
            $this->delete();
            return ['status' => true, 'message' => 'Benefit deleted successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to delete benefit.'];
        }
    }
}
