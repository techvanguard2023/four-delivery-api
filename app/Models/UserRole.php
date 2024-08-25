<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRole extends Model
{
    use HasFactory, SoftDeletes;

    // Define o relacionamento com a model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define o relacionamento com a model Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
