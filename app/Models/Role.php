<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_role_id'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role')->withTimestamps();
    }

    public function parentRole()
    {
        return $this->belongsTo(Role::class, 'parent_role_id');
    }

    public function childRoles()
    {
        return $this->hasMany(Role::class, 'parent_role_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get this role's permissions including inherited ones
     */
    public function getAllPermissions()
    {
        $permissions = $this->permissions;

        if ($this->parentRole) {
            $permissions = $permissions->merge($this->parentRole->getAllPermissions());
        }

        return $permissions->unique();
    }
}
