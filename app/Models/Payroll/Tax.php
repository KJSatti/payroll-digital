<?php

namespace App\Models\Payroll;

use App\Models\Employees\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;

class Tax extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'taxes';

    protected $fillable = [
        'employee_id',
        'federal_tax',
        'state_tax',
        'local_tax',
        'social_security_tax',
        'medicare_tax',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /* ============== Relations ============== */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /* ============== Validation ============== */
    protected static function rules(): array
    {
        return [
            'employee_id'         => 'required|exists:employees,id',
            'federal_tax'         => 'required|numeric|min:0',
            'state_tax'           => 'required|numeric|min:0',
            'local_tax'           => 'required|numeric|min:0',
            'social_security_tax' => 'required|numeric|min:0',
            'medicare_tax'        => 'required|numeric|min:0',
        ];
    }

    protected static function messages(): array
    {
        return [
            'employee_id.required' => 'Employee is required.',
            'employee_id.exists'   => 'Selected employee does not exist.',
            'federal_tax.required' => 'Federal tax is required.',
            'state_tax.required'   => 'State tax is required.',
            'local_tax.required'   => 'Local tax is required.',
            'social_security_tax.required' => 'Social Security tax is required.',
            'medicare_tax.required'        => 'Medicare tax is required.',
            '*.numeric'            => 'This field must be a number.',
            '*.min'                => 'This field must be at least 0.',
        ];
    }

    protected static function validate(array $data)
    {
        return Validator::make($data, self::rules(), self::messages());
    }

    /* ============== Queries ============== */
    public static function list($request = null): array
    {
        try {
            $q = self::with('employee')->latest();

            if ($request) {
                if ($request->filled('employee_id')) {
                    $q->where('employee_id', $request->employee_id);
                }
            }

            return [
                'status'  => true,
                'message' => 'Taxes fetched successfully.',
                'data'    => $q->get(),
            ];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to fetch taxes.', 'data' => collect()];
        }
    }

    /* ============== CRUD (with try/catch) ============== */
    public static function storeFromRequest($request): array
    {
        try {
            $payload = $request->only([
                'employee_id',
                'federal_tax',
                'state_tax',
                'local_tax',
                'social_security_tax',
                'medicare_tax',
            ]);

            $validator = self::validate($payload);
            if ($validator->fails()) {
                return ['status' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()];
            }

            $payload['created_by'] = auth()->id();
            self::create($payload);

            return ['status' => true, 'message' => 'Tax record added successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to add tax record.'];
        }
    }

    public function updateFromRequest($request): array
    {
        try {
            $payload = $request->only([
                'employee_id',
                'federal_tax',
                'state_tax',
                'local_tax',
                'social_security_tax',
                'medicare_tax',
            ]);

            $validator = self::validate($payload);
            if ($validator->fails()) {
                return ['status' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()];
            }

            $payload['updated_by'] = auth()->id();
            $this->update($payload);

            return ['status' => true, 'message' => 'Tax record updated successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to update tax record.'];
        }
    }

    public function deleteSafely(): array
    {
        try {
            $this->update(['deleted_by' => auth()->id()]);
            $this->delete();
            return ['status' => true, 'message' => 'Tax record deleted successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to delete tax record.'];
        }
    }
}
