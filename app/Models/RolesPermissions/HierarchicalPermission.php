<?php

namespace App\Models;

use Spatie\Permission\Models\Permission;

class HierarchicalPermission extends Permission
{
    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }
}
