<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_structure_id'];

    public function parentStructure() 
    {
        return $this->belongsTo(Structure::class, 'parent_structure_id');
    }

    public function childStructures() 
    {
        return $this->hasMany(Structure::class, 'parent_structure_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
