<?php

namespace App\Models\Attendence;

use App\Models\Employees\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Attendence extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'attendences';

    protected $fillable = [
        'employee_id',
        'date',
        'time_in',
        'time_out',
        'total_hours_worked',
        'overtime_hours',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /* ================= Relationships ================= */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /* ================= Validation ================= */
    protected static function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time_in' => 'required|date_format:H:i',
            'time_out' => 'required|date_format:H:i',
            'total_hours_worked' => 'nullable|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
        ];
    }

    protected static function messages(): array
    {
        return [
            'employee_id.required' => 'Employee is required.',
            'employee_id.exists' => 'Selected employee does not exist.',
            'date.required' => 'Date is required.',
            'date.date' => 'Date must be a valid date.',
            'time_in.required' => 'Time in is required.',
            'time_in.date_format' => 'Time in must be in H:i format (e.g., 09:00).',
            'time_out.required' => 'Time out is required.',
            'time_out.date_format' => 'Time out must be in H:i format (e.g., 17:30).',
            'total_hours_worked.numeric' => 'Total hours must be a number.',
            'overtime_hours.numeric' => 'Overtime hours must be a number.',
        ];
    }

    protected static function validate(array $data)
    {
        return Validator::make($data, self::rules(), self::messages());
    }

    /* ================= Helpers ================= */
    protected static function computeHours(?string $in, ?string $out): float
    {
        if (!$in || !$out) {
            return 0.0;
        }

        try {
            $start = Carbon::createFromFormat('H:i', $in);
            $end = Carbon::createFromFormat('H:i', $out);

            // If out is past midnight, count as next day
            if ($end->lessThan($start)) {
                $end->addDay();
            }
            return round($start->floatDiffInHours($end), 2);
        } catch (\Throwable $e) {
            return 0.0;
        }
    }

    /* ================= Queries ================= */
    public static function list($request = null): array
    {
        try {
            $q = self::with('employee')->select('attendences.*');

            if ($request) {
                if ($request->filled('employee_id')) {
                    $q->where('employee_id', $request->employee_id);
                }
                if ($request->filled('date')) {
                    $q->whereDate('date', $request->date);
                }
                if ($request->filled('date_from')) {
                    $q->whereDate('date', '>=', $request->date_from);
                }
                if ($request->filled('date_to')) {
                    $q->whereDate('date', '<=', $request->date_to);
                }
            }

            $q = $q->orderBy('date', 'desc')->get();

            return [
                'status' => true,
                'message' => 'Attendances fetched successfully.',
                'data' => $q,
            ];
        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => 'Failed to fetch attendances.',
                'data' => collect(),
            ];
        }
    }

    /* ================= CRUD (with validation + try/catch) ================= */

    public static function storeFromRequest($request): array
    {
        try {
            $payload = $request->only([
                'employee_id', 'date', 'time_in', 'time_out', 'total_hours_worked', 'overtime_hours',
            ]);

            $validator = self::validate($payload);
            if ($validator->fails()) {
                return ['status' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()];
            }

            if (empty($payload['total_hours_worked'])) {
                $payload['total_hours_worked'] = self::computeHours($payload['time_in'], $payload['time_out']);
            }
            $payload['overtime_hours'] = $payload['overtime_hours'] ?? 0;
            $payload['created_by'] = auth()->id();

            self::create($payload);

            return ['status' => true, 'message' => 'Attendance added successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to add attendance.'];
        }
    }

    public function updateFromRequest($request): array
    {
        try {
            $payload = $request->only([
                'employee_id', 'date', 'time_in', 'time_out', 'total_hours_worked', 'overtime_hours',
            ]);

            $validator = self::validate($payload);
            if ($validator->fails()) {
                return ['status' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()];
            }

            if (empty($payload['total_hours_worked']) || $request->filled('time_in') || $request->filled('time_out')) {
                $payload['total_hours_worked'] = self::computeHours($payload['time_in'], $payload['time_out']);
            }
            $payload['overtime_hours'] = $payload['overtime_hours'] ?? 0;
            $payload['updated_by'] = auth()->id();

            $this->update($payload);

            return ['status' => true, 'message' => 'Attendance updated successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to update attendance.'];
        }
    }

    public function deleteSafely(): array
    {
        try {
            $this->update(['deleted_by' => auth()->id()]);
            $this->delete();
            return ['status' => true, 'message' => 'Attendance deleted successfully.'];
        } catch (\Throwable $e) {
            return ['status' => false, 'message' => 'Failed to delete attendance.'];
        }
    }
}
