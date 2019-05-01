<?php

namespace App;

use App\Library\APIHelper;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';

    public function get_cache_file($name)
    {
        return "$this->name\_$name";
    }

    public function url($uri)
    {
        return APIHelper::get_parametrized_url($this->shop_key, $this->shop_secret, $this->name, $uri);
    }
}
