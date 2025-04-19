<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDiscount extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'min_quantity', 'discounted_price'];
    protected $casts = [
        'min_quantity' => 'integer',
        'discounted_price' => 'decimal:2',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

}
