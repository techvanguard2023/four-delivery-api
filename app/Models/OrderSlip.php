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
        'percentage_tax',
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

    public function payments()
    {
        return $this->hasMany(Payment::class);
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

    public function applyAdjustments(): void
    {
        $discount = $this->discount ?? 0;
        $couvert = $this->couvert ?? 0;
        $percentageTax = $this->percentage_tax ?? 0;

        $priceAfterDiscount = max(0, $this->total_price - $discount);
        $priceWithCouvert = $priceAfterDiscount + $couvert;
        $taxAmount = ($percentageTax / 100) * $priceWithCouvert;

        $this->total_price_with_discount = round($priceWithCouvert + $taxAmount, 2);
    }

    public function removeDiscount(): void
    {
        $this->total_price_with_discount += $this->discount ?? 0;
        $this->discount = null;
    }

    public function removeCouvert(): void
    {
        $this->total_price_with_discount -= $this->couvert ?? 0;
        $this->couvert = null;
    }

    public function removePercentageTax(): void
    {
        if ($this->percentage_tax && $this->total_price_with_discount > 0) {
            $base = $this->total_price_with_discount / (1 + ($this->percentage_tax / 100));
            $taxAmount = $this->total_price_with_discount - $base;
            $this->total_price_with_discount -= $taxAmount;
            $this->percentage_tax = null;
        }
    }
}
