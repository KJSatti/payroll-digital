<?php

namespace App\Models\RolesPermissions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Permission extends Model
{
    protected $fillable = ['name'];

    protected $attributes = [
        'guard_name' => 'web',
    ];
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }

    public static function getAll()
    {
        return self::orderBy('id', 'desc')->get();
    }

    public static function createPermission($request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'name' => 'required|string|unique:permissions,name',
            ])->validate();

            return self::create($validated);
        } catch (\Throwable $th) {
            Log::error('Permission creation failed: ' . $th->getMessage());
            throw $th;
        }
    }

    public function updatePermission($request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'name' => 'required|string|unique:permissions,name,' . $this->id,
            ])->validate();

            $this->update($validated);
            return $this;
        } catch (\Throwable $th) {
            Log::error('Permission update failed: ' . $th->getMessage());
            throw $th;
        }
    }

    public function deletePermission()
    {
        try {
            $this->roles()->detach();
            return $this->delete();
        } catch (\Throwable $th) {
            Log::error('Permission deletion failed: ' . $th->getMessage());
            throw $th;
        }
    }
}
