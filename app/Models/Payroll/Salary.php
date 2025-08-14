<?php

namespace App\Models\Payroll;

use App\Models\Employees\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'basic_salary',
        'bonuses',
        'deductions',
        'overtime_pay',
        'net_salary',
        'pay_period',
        'pay_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public static function getAll($request = null)
    {
        $query = self::with('employee');

        if ($request) {
            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }
            if ($request->filled('pay_period')) {
                $query->whereRaw('LOWER(pay_period) = ?', [strtolower($request->pay_period)]);
            }
            if ($request->filled('pay_date')) {
                $query->whereDate('pay_date', $request->pay_date);
            }
        }

        return $query->latest()->get();
    }

    public static function storeData($request)
    {
        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();
            $data['net_salary'] = $data['basic_salary'] + $data['bonuses'] + $data['overtime_pay'] - $data['deductions'];
            self::create($data);
            return ['status' => true, 'message' => 'Salary record added successfully.'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function updateData($request)
    {
        try {
            $data = $request->all();
            $data['updated_by'] = auth()->id();
            $data['net_salary'] = $data['basic_salary'] + $data['bonuses'] + $data['overtime_pay'] - $data['deductions'];
            $this->update($data);
            return ['status' => true, 'message' => 'Salary record updated successfully.'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function deleteData()
    {
        try {
            $this->update(['deleted_by' => auth()->id()]);
            $this->delete();
            return ['status' => true, 'message' => 'Salary record deleted successfully.'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}