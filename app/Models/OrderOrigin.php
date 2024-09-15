<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderOrigin extends Model
{
    protected $fillable = ['name', 'description'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
