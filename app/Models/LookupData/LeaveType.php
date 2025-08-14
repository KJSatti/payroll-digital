<?php

namespace App\Models\LookupData;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function validateData($data, $isUpdate = false)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:leave_types,name' . ($isUpdate ? ',' . $data['id'] : ''),
        ];

        return Validator::make($data, $rules);
    }

    public static function createLeaveType($request)
    {
        try {
            $validator = self::validateData($request->all());
            if ($validator->fails()) {
                return ['status' => false, 'errors' => $validator->errors()];
            }

            $data = $request->only(['name']);
            $data['created_by'] = Auth::id();

            self::create($data);
            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'errors' => $e->getMessage()];
        }
    }

    public static function updateLeaveType($request, $id)
    {
        try {
            $LeaveType = self::findOrFail($id);

            $validator = self::validateData(array_merge($request->all(), ['id' => $id]), true);
            if ($validator->fails()) {
                return ['status' => false, 'errors' => $validator->errors()];
            }

            $data = $request->only(['name']);
            $data['updated_by'] = Auth::id();

            $LeaveType->update($data);
            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'errors' => $e->getMessage()];
        }
    }

    public static function deleteLeaveType($id)
    {
        try {
            $LeaveType = self::findOrFail($id);
            $LeaveType->deleted_by = Auth::id();
            $LeaveType->save();
            $LeaveType->delete();
            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'errors' => $e->getMessage()];
        }
    }
}