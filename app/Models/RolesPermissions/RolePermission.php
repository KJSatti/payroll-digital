<?php

namespace App\Models\RolesPermissions;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    // Optional: specify table name since it's non-standard
    protected $table = 'role_permissions';

    // Disable timestamps for pivot table
    public $timestamps = false;

    // Optional: define fillable if you manually insert/update
    protected $fillable = ['role_id', 'permission_id'];
}
