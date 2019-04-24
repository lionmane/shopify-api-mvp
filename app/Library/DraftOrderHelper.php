<?php
/**
 * Created by PhpStorm.
 * User: mjwunderlich
 * Date: 4/23/19
 * Time: 7:48 AM
 */

namespace App\Library;


class DraftOrderHelper
{
    protected static function fetch_orders()
    {
        // Return cached orders if possible
        if ($response = CacheFileHelper::load('draft_orders.json')) {
            return $response;
        }

        try {
            $url = APIHelper::get_url("/admin/api/2019-04/draft_orders.json");
            $orders = CurlHelper::exec($url);
            $orders = $orders['draft_orders'];
            CacheFileHelper::save('draft_orders.json', $orders);
            return $orders;
        } catch (\Exception $error) {
            error_log($error->getMessage());
            return [];
        }
    }

    public static function get_orders()
    {
        $orders = self::fetch_orders();
        return $orders;
    }

    public static function prepare_order($cart)
    {
        $json = [
            'line_items' => [],
            'customer' => [
                'id' => $cart->customer_id,
                'email' => $cart->customer_email
            ],
            "use_customer_default_address" => true
        ];
        foreach ($cart->items as $item) {
            $json['line_items'][] = [
                'custom' => false,
                'requires_shipping' => false,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'quantity' => 1,
            ];
            break;
        }

        return ["draft_order" => $json];
    }

    public static function create_order($cart)
    {
        $data = self::prepare_order($cart);
        $url = APIHelper::get_url("/admin/api/2019-04/draft_orders.json");
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, ['Content-Type' => 'application/json']);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log("ERROR: " . curl_error($ch));
        }
        curl_close($ch);
        return $response;
    }
}
