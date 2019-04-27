<?php

use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendor = new \App\Vendor();
        $vendor->display_name = env('VENDOR_1_DISPLAY_NAME');
        $vendor->name = env('VENDOR_1_NAME');
        $vendor->shop_url = env('VENDOR_1_NAME') . '.myshopify.com';
        $vendor->shop_key =env('VENDOR_1_KEY');
        $vendor->shop_secret = env('VENDOR_1_SECRET');
        $vendor->save();

        $vendor = new \App\Vendor();
        $vendor->display_name = env('VENDOR_2_DISPLAY_NAME');
        $vendor->name = env('VENDOR_2_NAME');
        $vendor->shop_url = env('VENDOR_2_NAME') . '.myshopify.com';
        $vendor->shop_key =env('VENDOR_2_KEY');
        $vendor->shop_secret = env('VENDOR_2_SECRET');
        $vendor->save();
    }
}
