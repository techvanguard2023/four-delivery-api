<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plan;

class Feature extends Model
{
    protected $table = 'features';

    protected $fillable = ['name', 'plan_id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_features')->withTimestamps();
    }
}
