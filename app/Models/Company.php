<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'fantasy_name',
        'slug',
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

    public function plans()
    {
        return $this->hasMany(CompanyPlan::class);
    }

    public function companyPlans()
    {
        return $this->hasMany(CompanyPlan::class);
    }

}
