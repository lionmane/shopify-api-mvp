<?php
/**
 * Created by PhpStorm.
 * User: mjwunderlich
 * Date: 4/23/19
 * Time: 10:24 PM
 */

namespace App\Library;


class APIHelper
{
    public static function get_url($uri = false)
    {
        $key = env('SHOPIFY_KEY');
        $secret = env('SHOPIFY_SECRET');
        $store = env('SHOPIFY_NAME');
        $initial_url = "https://$key:$secret@$store.myshopify.com";
        if ($uri !== false) {
            if ($uri[0] != '/')
                $initial_url = $initial_url . '/';
            return $initial_url . $uri;
        }
        return $initial_url;
    }

    public static function get_parametrized_url($key, $secret, $store, $uri)
    {
        $initial_url = "https://$key:$secret@$store.myshopify.com";
        if ($uri !== false) {
            if ($uri[0] != '/')
                $initial_url = $initial_url . '/';
            return $initial_url . $uri;
        }
        return $initial_url;
    }
}