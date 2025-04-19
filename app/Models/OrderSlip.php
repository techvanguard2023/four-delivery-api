<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderSlip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'customer_name',
        'position',
        'total_price',
        'discount',
        'couvert',
        'total_price_with_discount',
        'status_id',
        'payment_status',
        'last_status_id',
        'last_payment_status',
        'order_type_id',
        'order_origin_id',
        'duration',
        'user_id',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function orderSlipItems()
    {
        return $this->hasMany(OrderSlipItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function orderType()
    {
        return $this->belongsTo(OrderType::class);
    }

    public function orderOrigin()
    {
        return $this->belongsTo(OrderOrigin::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position', 'asc');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function adjustments()
    {
        return $this->hasMany(OrderSlipAdjustment::class);
    }
}