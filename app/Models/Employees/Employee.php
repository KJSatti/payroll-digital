<?php

namespace App\Models\Employees;

use App\Models\Attendance\Attendance;
use App\Models\Attendance\Leave;
use App\Models\Attendance\Timesheet;
use App\Models\HR\Benefit;
use App\Models\HR\Deduction;
use App\Models\HR\EmployeeDocument;
use App\Models\HR\ExpenseClaim;
use App\Models\HR\PerformanceReview;
use App\Models\HR\SkillsAndQualification;
use App\Models\HR\TrainingAndDevelopment;
use App\Models\LookupData\Department;
use App\Models\LookupData\Position;
use App\Models\Payroll\Salary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'date_of_birth',
        'hire_date',
        'department_id',
        'position_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relationships

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function salary()
    {
        return $this->hasMany(Salary::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function emergencyContacts()
    {
        return $this->hasMany(EmployeeEmergencyContact::class);
    }

    public function bankInformation()
    {
        return $this->hasOne(EmployeeBankInformation::class);
    }

    public function addresses()
    {
        return $this->hasMany(EmployeeAddress::class);
    }

    public function benefits()
    {
        return $this->hasMany(Benefit::class);
    }

    public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }

    public function expenseClaims()
    {
        return $this->hasMany(ExpenseClaim::class);
    }

    public function performanceReviews()
    {
        return $this->hasMany(PerformanceReview::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function trainings()
    {
        return $this->hasMany(TrainingAndDevelopment::class);
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function skills()
    {
        return $this->hasMany(SkillsAndQualification::class);
    }

    public function deployments()
    {
        return $this->hasMany(EmployeeDeployment::class);
    }

    public static function getAllEmployees($request = false)
    {
        if ($request === false) {
            $request = request();
        }
        $perPage = $request->integer('per_page', 15);

        $query = self::with(['department', 'position']);
        
        if ($request) {
           
            if ($request->filled('first_name')) {
                $query->where('first_name', 'ILIKE', '%' . $request->first_name . '%');
            }

            if ($request->filled('last_name')) {
                $query->where('last_name', 'ILIKE', '%' . $request->last_name . '%');
            }

            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }

            if ($request->filled('position_id')) {
                $query->where('position_id', $request->position_id);
            }

            if ($request->filled('hire_date')) {
                $query->whereDate('hire_date', $request->hire_date);
            }
        }

        return $query->latest()->paginate($perPage);
    }

    public static function addEmployee($request)
    {
        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();
            self::create($data);
            return ['status' => true, 'message' => 'Employee added successfully.'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Failed to add employee.'];
        }
    }

    public static function updateEmployee($request, $id)
    {
        try {
            $employee = self::findOrFail($id);
            $data = $request->all();
            $data['updated_by'] = auth()->id();
            $employee->update($data);
            return ['status' => true, 'message' => 'Employee updated successfully.'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Failed to update employee.'];
        }
    }

    public static function deleteEmployee($id)
    {
        try {
            $employee = self::findOrFail($id);
            $employee->deleted_by = auth()->id();
            $employee->save();
            $employee->delete();
            return ['status' => true, 'message' => 'Employee deleted successfully.'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Failed to delete employee.'];
        }
    }
}
