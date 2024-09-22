<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'slug', 'price', 'duration', 'status'];

    public function features()
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function companyPlans()
    {
        return $this->hasMany(CompanyPlan::class);
    }
}
