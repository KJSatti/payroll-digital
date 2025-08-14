<?php

namespace App\Models\RolesPermissions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Role extends Model
{
    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    protected $attributes = [
        'guard_name' => 'web',
    ];

    public static function getAll()
    {
        return self::with('permissions')
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function createRole($request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'name' => 'required|string|unique:roles,name',
                'permissions' => 'nullable|array',
            ])->validate();

            $role = self::create(['name' => $validated['name']]);

            if (!empty($validated['permissions'])) {
                foreach ($validated['permissions'] as $permissionId) {
                    RolePermission::create([
                        'role_id' => $role->id, // or use $id if passed directly
                        'permission_id' => $permissionId,
                    ]);
                }
            }

            return $role;
        } catch (\Throwable $th) {
            Log::error('Role creation failed: ' . $th->getMessage());
            throw $th;
        }
    }

    public static function updateRole($id, $request)
    {
        try {
            $request = request();
            $validated = Validator::make($request->all(), [
                'name' => 'required|string|unique:roles,name,' . $id,
                'permissions' => 'nullable|array',
            ])->validate();

            $role = self::findOrFail($id);
            $role->update(['name' => $validated['name']]);
            if (!empty($validated['permissions'])) {
                foreach ($validated['permissions'] as $permissionId) {
                    RolePermission::create([
                        'role_id' => $role->id, // or use $id if passed directly
                        'permission_id' => $permissionId,
                    ]);
                }
            } else {
                $role->permissions()->detach(); // Remove all if none selected
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Role update failed: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteRole()
    {
        try {
            return $this->delete();
        } catch (\Throwable $e) {
            Log::error('Role deletion failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
