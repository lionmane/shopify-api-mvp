<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';
    protected $casts = [
        'metadata' => 'array'
    ];
}
