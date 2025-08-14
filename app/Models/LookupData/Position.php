<?php

namespace App\Models\LookupData;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Exception;

class Position extends Model
{
    protected $fillable = ['title', 'department_id', 'description'];

    public static function addPosition($data)
    {
        try {
            $validator = Validator::make($data, [
                'title' => 'required|string|max:255',
                'department_id' => 'required',
                'description' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return ['success' => false, 'errors' => $validator->errors()];
            }

            self::create($data);
            return ['success' => true, 'message' => 'Position added successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function updatePosition($id, $data)
    {
        try {
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'department_id' => 'required',
                'description' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return ['success' => false, 'errors' => $validator->errors()];
            }

            $position = self::findOrFail($id);
            $position->update($data);
            return ['success' => true, 'message' => 'Position updated successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function deletePosition($id)
    {
        try {
            $position = self::findOrFail($id);
            $position->delete();
            return ['success' => true, 'message' => 'Position deleted successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public static function getPositions()
    {
        return self::join('departments as d', 'd.id', 'positions.department_id')->select('positions.*', 'd.name as department_name')->get();
    }
}