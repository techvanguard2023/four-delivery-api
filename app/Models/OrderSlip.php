<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderSlip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['company_id', 'customer_name', 'total_price', 'status_id', 'payment_status', 'last_status_id', 'last_payment_status', 'order_type_id', 'location', 'order_origin_id', 'position'];


    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
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
}
