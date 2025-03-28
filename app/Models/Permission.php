<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get all users who have this permission through roles.
     */
    public function users()
    {
        return $this->hasManyThrough(User::class, Role::class);
    }
}
