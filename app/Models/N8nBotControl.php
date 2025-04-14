<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class N8nBotControl extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'instance_name', 'bot_name', 'description', 'is_active'];
}
