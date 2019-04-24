<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    public function items()
    {
        return $this->hasMany('App\CartItem');
    }

    public function total()
    {
        return CartItem::query()
            ->where('cart_id', $this->id)
            ->sum('total_price');
    }

    public function status()
    {
        return $this->order_id ? 'Completed' : 'Open';
    }
}
