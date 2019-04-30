<?php

namespace App\Library;
use Psy\Exception\ErrorException;

/**
 * Created by PhpStorm.
 * User: mjwunderlich
 * Date: 4/22/19
 * Time: 7:43 PM
 */
class ProductHelper
{
    /**
     * Fetches product information from Shopify. If a cache is available and hasn't yet expired, then
     * this method will fetch product information from the cache file instead.
     *
     * @return array
     */
    public static function fetch_products()
    {
        // Return cached products if possible
        if (false !== ($response = CacheFileHelper::load('products.json', 6000))) {
            return $response;
        }

        $initial_url = APIHelper::get_url("/admin/api/2019-04/products.json");

        $query_url = "";
        $products = [];
        $max_iterations = 5;
        do {
            $max_iterations --;
            try {
                $response = CurlHelper::exec($initial_url . $query_url);
                if (!$response || empty($response))
                    break;

                if (!is_array($response) || empty($response) || !array_key_exists('products', $response))
                    break;

                $response = $response['products'];
                if (!count($response))
                    break;

                $last_id = last($response)['id'];
                $query_url = "?since_id=$last_id";
                $products = array_merge($products, $response);
            } catch (\Exception $error) {
                error_log($error->getMessage());
                break;
            }
        } while ($max_iterations > 0);

        // Cache all results
        file_put_contents(base_path('products.json'), json_encode($products, JSON_PRETTY_PRINT));

        return $products;
    }

    /**
     * Returns a list of all product variants.
     *
     * @return array
     */
    public static function get_products()
    {
        $products = self::fetch_products();
        $results = [];
        foreach ($products as $product) {
            self::get_product_variants($product, $results);
        }
        return $results;
    }

    /**
     * Gets all variants from a product and treats them as actual products. Instead of returning
     * an array of items, these are appended to the $results array.
     *
     * @param $product
     * @param $results
     */
    public static function get_product_variants($product, &$results)
    {
        // Get a dictionary of images (will be necessary for mapping variant images
        $images = array_combine(array_column($product['images'], 'id'), $product['images']);
        $default_image = $product['image'];
        $vendor = $product['vendor'];
        $name = $product['title'];

        // Flatten all variants as if they were individual products
        foreach ($product['variants'] as $variant) {
            $variant_id = $variant['id'];
            $variant_name = $variant['title'];
            $price = $variant['price'];
            if (!is_null($variant['image_id']))
                $image = $images[$variant['image_id']]['src'];
            else
                $image = $default_image['src'];

            $results[] = [
                'id' => $product['id'],
                'product_id' => $product['id'],
                'variant_id' => $variant_id,
                'vendor' => $vendor,
                'name' => $name,
                'variant_name' => $variant_name,
                'price' => $price,
                'image' => $image
            ];
        }
    }

    /**
     * Searches for a particular product by Variant ID.
     * Uses the products cache if available.
     *
     * @param $id
     * @return mixed
     */
    public static function product_by_variant_id($id)
    {
        $products = self::get_products();
        $products = array_combine(array_column($products, 'variant_id'), $products);
        return $products[$id];
    }
}
