<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPlan extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'plan_id', 'start_date', 'end_date', 'status'];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
