<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Subscription;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'fantasy_name',
        'slug',
        'logo_url',
        'cnpj',
        'cpf',
        'email',
        'address',
        'number',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'country',
        'phone',
        'whatsapp',
        'website'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Assinatura atual (ativa e com maior end_date)
    public function currentSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->latestOfMany('end_date'); // pega a de maior end_date
    }

}
