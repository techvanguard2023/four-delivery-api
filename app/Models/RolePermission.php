<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolePermission extends Model
{
    use HasFactory, SoftDeletes;

    // Define o relacionamento com a model Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Define o relacionamento com a model Permission
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
