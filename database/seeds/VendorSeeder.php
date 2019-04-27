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
        $vendor->display_name = 'The Conservatory NYC';
        $vendor->name = 'the-conservatory-nyc';
        $vendor->shop_url = 'the-conservatory-nyc.myshopify.com';
        $vendor->shop_key = 'f37c91172b69f500e3055debdf52009b';
        $vendor->shop_secret = 'f973980b116a88c72d1bb8087228def5';
        $vendor->save();

        $vendor = new \App\Vendor();
        $vendor->display_name = 'LionMane Demo Store';
        $vendor->name = 'lionmane-demo-store';
        $vendor->shop_url = 'lionmane-demo-store.myshopify.com';
        $vendor->shop_key = '47a524a480896658bad700d1ddcca40a';
        $vendor->shop_secret = '75d5d550ad38a511f3843ef73e8dd358';
        $vendor->save();
    }
}
