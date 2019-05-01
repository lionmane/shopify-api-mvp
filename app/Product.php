<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $casts = [
        'metadata' => 'array'
    ];

    public function vendor()
    {
        return $this->belongsTo('\App\Vendor');
    }

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class);
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

    public static function find_by_variant_id($variant_id)
    {
        $variant = ProductVariant::query()->where('shopify_variant_id', $variant_id)->first();
        if (!$variant)
            return null;
        return $variant->product;
    }
}
