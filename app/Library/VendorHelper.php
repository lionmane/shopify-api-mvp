<?php
/**
 * Created by PhpStorm.
 * User: mjwunderlich
 * Date: 4/26/19
 * Time: 9:24 PM
 */

namespace App\Library;


use App\Vendor;

class VendorHelper
{
    public static function get_vendor()
    {
        $store = env('SHOPIFY_NAME');
        $vendor = Vendor::where('name', $store)->first();
        if (!$vendor) {
            throw new \Exception("Vendor [$store] not found");
        }
        return $vendor;
    }
}