<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $casts = [
        'metadata' => 'array'
    ];

    public function get_metadata($assoc=true)
    {
        return json_decode($this->metadata, $assoc);
    }
}
