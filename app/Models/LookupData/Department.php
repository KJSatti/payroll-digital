<?php

namespace App\Models\LookupData;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Exception;

class Department extends Model
{
    protected $fillable = ['name', 'description'];

    public static function addDepartment($data)
    {
        try {
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return ['success' => false, 'errors' => $validator->errors()];
            }

            self::create($data);
            return ['success' => true, 'message' => 'Department added successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function updateDepartment($id, $data)
    {
        try {
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return ['success' => false, 'errors' => $validator->errors()];
            }

            $department = self::findOrFail($id);
            $department->update($data);
            return ['success' => true, 'message' => 'Department updated successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function deleteDepartment($id)
    {
        try {
            $department = self::findOrFail($id);
            $department->delete();
            return ['success' => true, 'message' => 'Department deleted successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function getDepartments()
    {
        return self::all();
    }
}