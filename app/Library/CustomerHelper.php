<?php
/**
 * Created by PhpStorm.
 * User: mjwunderlich
 * Date: 4/23/19
 * Time: 7:48 AM
 */

namespace App\Library;


class CustomerHelper
{
    protected static function fetch_customers()
    {
        // Return cached customers if possible
        if ($response = CacheFileHelper::load('customers.json', 6000)) {
            return $response;
        }

        try {
            $url = APIHelper::get_url("/admin/api/2019-04/customers.json");
            $customers = CurlHelper::exec($url);
            $customers = $customers['customers'];
            CacheFileHelper::save('customers.json', $customers);
            return $customers;
        } catch (\Exception $error) {
            error_log($error->getMessage());
            return [];
        }
    }

    public static function get_customers()
    {
        $customers_data = self::fetch_customers();
        $customers = [];
        foreach ($customers_data as $item) {
            $customers[] = [
                'id' => $item['id'],
                'last_order_id' => $item['last_order_id'],
                'last_order_name' => $item['last_order_name'],
                'first_name' => $item['first_name'],
                'last_name' => $item['last_name'],
                'email' => $item['email'],
                'total_spent' => $item['total_spent']
            ];
        }
        return $customers;
    }

    public static function customer_by_id($id)
    {
        $customers = self::fetch_customers();
        $customers = array_combine(array_column($customers, 'id'), $customers);
        return $customers[$id];
    }
}
