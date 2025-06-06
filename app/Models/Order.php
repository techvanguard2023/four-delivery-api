<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['company_id', 'customer_id', 'delivery_person_id', 'total_price', 'discount', 'total_price_with_discount', 'status_id', 'payment_status', 'last_status_id', 'last_payment_status', 'order_type_id', 'order_origin_id', 'position'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveryPerson()
    {
        return $this->belongsTo(DeliveryPerson::class, 'delivery_person_id');
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

    public function deliveryLocation()
    {
        return $this->belongsTo(DeliveryLocation::class);
    }
}
