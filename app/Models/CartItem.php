<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'price',
        'variation_type_options_ids'
    ];

    // Indique à Laravel de gérer la conversion Array <-> JSON automatiquement
    protected $casts = [
        'variation_type_options_ids' => 'array', // ou 'json'
    ];
}
