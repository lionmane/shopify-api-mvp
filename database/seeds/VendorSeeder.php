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
        $count = env('VENDOR_COUNT', 0);
        for ($i=1; $i <= $count; ++$i) {
            $vendor = new \App\Vendor();
            $prefix = 'VENDOR_' . $i;
            $vendor->display_name = env($prefix . '_DISPLAY_NAME');
            $vendor->name = env($prefix . '_NAME');
            $vendor->shop_url = env($prefix . '_NAME') . '.myshopify.com';
            $vendor->shop_key = env($prefix . '_KEY');
            $vendor->shop_secret = env($prefix . '_SECRET');
            $vendor->save();
        }
    }
}
