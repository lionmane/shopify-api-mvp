<?php
/**
 * Created by PhpStorm.
 * User: mjwunderlich
 * Date: 4/23/19
 * Time: 7:48 AM
 */

namespace App\Library;


use App\Vendor;

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

    public static function prepare_order($items, $name='', $notes='')
    {
        $json = [
            'line_items' => [],
            'name' => $name,
            'note' => $notes,
            "use_customer_default_address" => true
        ];
        foreach ($items as $item) {
            $json['line_items'][] = [
                'custom' => false,
                'requires_shipping' => false,
                'product_id' => $item->variant->shopify_product_id,
                'variant_id' => $item->variant->shopify_variant_id,
                'quantity' => $item->quantity,
            ];
            break;
        }

        return ["draft_order" => $json];
    }

    public static function create_order($cart, $name='', $notes='')
    {
        $items_per_vendor = self::get_cart_products_per_vendor($cart);
        $responses = [];
        foreach ($items_per_vendor as $vendor_id => $items) {
            $data = self::prepare_order($items, $name, $notes);
            $vendor = Vendor::query()->find($vendor_id);
            $url = $vendor->url("/admin/api/2019-04/draft_orders.json");
            $headers = [
                'X-Shopify-Access-Token: ' . env('SHOPIFY_SECRET'),
                'content-type: application/json; charset=utf-8'
            ];
            $responses[$vendor_id] = CurlHelper::exec($url, 'POST', json_encode($data), $headers, 1, true);
        }

        return $responses;
    }

    public static function get_cart_vendors($cart)
    {
        $vendors = [];
        foreach ($cart->items as $item) {
            $product = $item->product;
            $vendor_id = $product->vendor_id;
            if (!array_key_exists($vendor_id, $vendors))
                $vendors[$vendor_id] = $product->vendor;
        }
        return $vendors;
    }

    public static function get_cart_products_per_vendor($cart)
    {
        $vendor_bucket = [];
        foreach ($cart->items as $item) {
            $vendor_id = $item->product->vendor->id;
            if (!array_key_exists($vendor_id, $vendor_bucket))
                $vendor_bucket[$vendor_id] = [];
            $vendor_bucket[$vendor_id][] = $item;
        }
        return $vendor_bucket;
    }
}
