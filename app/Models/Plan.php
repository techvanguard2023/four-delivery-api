<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subscription;
use App\Models\Feature;

class Plan extends Model
{
    use HasFactory;

    protected $casts = [
        'price' => 'decimal:2',
    ];

    protected $fillable = [
        'name', 
        'description', 
        'slug', 
        'price', 
        'stripe_price_id',
        'duration', 
        'status',
        'is_free',
        'is_popular',
    ];

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'plan_features');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
