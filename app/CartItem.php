<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';

    public function cart()
    {
        return $this->belongsTo('\App\Cart');
    }
}
