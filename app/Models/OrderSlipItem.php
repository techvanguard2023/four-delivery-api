<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderSlipItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['order_slip_id', 'item_id', 'quantity', 'price', 'observation'];

    public function orderSlip()
    {
        return $this->belongsTo(OrderSlip::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
