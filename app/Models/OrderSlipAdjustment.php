<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSlipAdjustment extends Model
{
    protected $fillable = ['order_slip_id', 'type', 'value_type', 'value', 'description'];

    public function orderSlip()
    {
        return $this->belongsTo(OrderSlip::class);
    }
}