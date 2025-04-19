<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderSlipItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_id',
        'quantity',
        'unit_price',
        'total_price',
        'observation',
    ];
    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];    

    public function orderSlip()
    {
        return $this->belongsTo(OrderSlip::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
