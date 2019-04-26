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

    public static function prepare_order($cart, $name='', $notes='')
    {
        $json = [
            'line_items' => [],
            'customer' => [
                'id' => $cart->customer_id
            ],
            'name' => $name,
            'note' => $notes,
            "use_customer_default_address" => true
        ];
        foreach ($cart->items as $item) {
            $json['line_items'][] = [
                'custom' => false,
                'requires_shipping' => false,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
            ];
            break;
        }

        return ["draft_order" => $json];
    }

    public static function create_order($cart, $name='', $notes='')
    {
        $data = self::prepare_order($cart, $name, $notes);
        $url = APIHelper::get_url("/admin/api/2019-04/draft_orders.json");
        $headers = [
            'X-Shopify-Access-Token: ' . env('SHOPIFY_SECRET'),
            'content-type: application/json; charset=utf-8'
        ];

        $response = CurlHelper::exec($url, 'POST', json_encode($data), $headers, 1, false);
        return $response;
    }
}
