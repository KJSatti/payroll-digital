<?php

namespace App\Http\Controllers\RolesPermissions;

use App\Http\Controllers\Controller;
use App\Models\RolesPermissions\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{

    public function index()
    {
        try {
            $permissions = Permission::getAll();
            return view('permissions.index', compact('permissions'));
        } catch (\Throwable $th) {
            Log::error('Failed to load roles: ' . $th->getMessage());
            return back()->with('error', 'Unable to load roles');
        }
    }

    public function create()
    {
        try {
            return view('permissions.create');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Unable to load create form.');
        }
    }

    public function store(Request $request)
    {
        $permission = Permission::createPermission($request);
        return redirect()->route('permissions.index')->with('success', 'Permission created successfully');
    }

    public function edit($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            return view('permissions.edit', compact('permission'));
        } catch (\Throwable $e) {
            return redirect()->route('permissions.index')->with('error', 'Failed to load edit page.');
        }
    }

    public function update(Request $request, Permission $permission)
    {
        $permission->updatePermission($request);
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        $permission->deletePermission();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully');
    }
}
