<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryPerson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'phone', 'vehicle'];

    public function deliveries()
    {
        return $this->hasMany(Order::class); // Considerando que um entregador pode estar vinculado a múltiplos pedidos
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
