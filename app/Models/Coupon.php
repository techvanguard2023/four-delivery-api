<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'type', 'value', 'usage_limit', 'used', 'expires_at', 'active', 'company_id'];

    protected $casts = [
        'expires_at' => 'datetime', // 👈 necessário
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isValid($orderTotal = null): bool
    {
        return $this->active &&
            (!$this->expires_at || $this->expires_at->isFuture()) &&
            (!$this->usage_limit || $this->used < $this->usage_limit);
    }

    public function applyDiscount($amount)
    {
        return $this->type === 'percentage'
            ? round($amount * ((100 - $this->value) / 100), 2)
            : max(0, round($amount - $this->value, 2));
    }
}
