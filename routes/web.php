<?php

use App\Http\Controllers\Employees\EmployeeController;
use App\Http\Controllers\HR\BenefitController;
use App\Http\Controllers\HR\DeductionController;
use App\Http\Controllers\LookupData\BenefitTypeController;
use App\Http\Controllers\LookupData\DeductionTypeController;
use App\Http\Controllers\LookupData\DepartmentController;
use App\Http\Controllers\LookupData\LeaveTypeController;
use App\Http\Controllers\LookupData\PaymentMethodController;
use App\Http\Controllers\LookupData\PositionController;
use App\Http\Controllers\Payroll\AttendenceController;
use App\Http\Controllers\Payroll\PaymentController;
use App\Http\Controllers\Payroll\SalaryController;
use App\Http\Controllers\Payroll\TaxController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolesPermissions\PermissionController;
use App\Http\Controllers\RolesPermissions\RoleController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('positions', PositionController::class);

    Route::resource('employees', EmployeeController::class);
    Route::resource('salaries', SalaryController::class);
    Route::resource('attendences', AttendenceController::class);
    Route::resource('taxes', TaxController::class);
    Route::resource('benefit-types', BenefitTypeController::class);
    Route::resource('benefits', BenefitController::class);

    Route::resource('deduction-types', DeductionTypeController::class);
    Route::resource('deductions', DeductionController::class);

    Route::resource('payment-methods', PaymentMethodController::class);
    Route::resource('payments', PaymentController::class);

    Route::resource('leave-types', LeaveTypeController::class);
    // Route::resource('leaves', LeaveController::class);

    Route::post('/users/{user}/assign-role', [UserController::class, 'assignRoleToUser'])->name('users.assign.role');
});

require __DIR__.'/auth.php';
