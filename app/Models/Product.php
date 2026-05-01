<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'token_cost',
        'stock',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
