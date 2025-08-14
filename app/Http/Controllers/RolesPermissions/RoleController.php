<?php

namespace App\Http\Controllers\RolesPermissions;

use App\Http\Controllers\Controller;
use App\Models\RolesPermissions\Permission;
use App\Models\RolesPermissions\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    // RoleController.php

    public function index()
    {
        try {
            $roles = Role::getAll();
            return view('roles.index', compact('roles'));
        } catch (\Throwable $th) {
            Log::error('Failed to load roles: ' . $th->getMessage());
            return back()->with('error', 'Unable to load roles');
        }
    }

    public function create()
    {
        try {
            $permissions = Permission::getAll();
            return view('roles.create', compact('permissions'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Unable to load create form.');
        }
    }
    public function store(Request $request)
    {
        Role::createRole($request);
        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    public function edit($id)
    {
        try {
            $permissions = Permission::getAll();
            $role = Role::findOrFail($id);
            return view('roles.edit', compact('role', 'permissions'));
        } catch (\Throwable $e) {
            return redirect()->route('roles.index')->with('error', 'Failed to load edit page.');
        }
    }

    public function update(Request $request, $id)
    {
        Role::updateRole($id, $request->all());
        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        $role->deleteRole();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
