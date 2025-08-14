<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $viewUsers = Permission::firstOrCreate(['name' => 'users.view']);
        $createUsers = Permission::firstOrCreate(['name' => 'users.create']);

        // Create role and assign permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([$viewUsers, $createUsers]);

        // Assign role to a user
        $user = User::find(1);
        if ($user) {
            $user->assignRole($admin);
        }
    }
}