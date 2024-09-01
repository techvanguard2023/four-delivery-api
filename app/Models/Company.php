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
        'cnpj',
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
}
