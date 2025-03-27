<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'structure_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function debitCards()
    {
        return $this->hasMany(DebitCard::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    // Retrieve all users under the same structure (Downstream)
    public function downstreamUsers()
    {
        return User::whereHas('structure', function ($query) {
            $query->where('parent_structure_id', $this->structure_id);
        })->get();
    }
}
