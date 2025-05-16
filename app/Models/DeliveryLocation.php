<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'tax',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
