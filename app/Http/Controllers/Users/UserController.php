<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\RolesPermissions\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::get();
        $users = User::with(['department', 'position'])->latest()->get();
        return view('users.index', compact('users', 'roles'));
    }

    public function assignRoleToUser(Request $request, $userId)
    {
        $validated = $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $success = User::assignRoleById($userId, $validated['role']);

        if ($success) {
            return redirect()->back()->with('success', 'Role assigned successfully.');
        }

        return redirect()->back()->with('error', 'Failed to assign role.');
    }
}
