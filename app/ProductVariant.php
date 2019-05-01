<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';
    protected $casts = [
        'metadata' => 'array'
    ];

    public function vendor()
    {
        return $this->belongsTo('\App\Vendor');
    }

    public function product()
    {
        return $this->belongsTo('\App\Product');
    }

    public function get_metadata($assoc=true)
    {
        return json_decode($this->metadata, $assoc);
    }

    public function get_metadata_field($field)
    {
        try {
            return $this->get_metadata()[$field];
        }
        catch (\Exception $e) {
            return null;
        }
    }
}
