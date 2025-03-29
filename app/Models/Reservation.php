<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'position',
        'customer_name',
        'contact_phone',
        'observation',
        'reserved_at',
        'duration_minutes',
        'status',
    ];

    protected $casts = [
        'reserved_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
