<?php
namespace App\Services;
use Shippo;
use Shippo_Address;
use Shippo_Shipment;
use App\User;
use App\CartItem;
class Shipping 
{
    public function __construct()
    {
        // Grab this private key from
        // .env and setup the Shippo api
        Shippo::setApiKey('shippo_test_5f9088dd2e8679db2c3dd326eb453fe4f51c426e');
    }
    /**
	 * Validate an address through Shippo service
	 *
	 * @param User $user
	 * @return Shippo_Adress
	 */
	public static function validateAddress(User $user)
	{
	    // Grab the shipping address from the User model
	    $toAddress = $user->shippingAddress();
	    // Pass a validate flag to Shippo
	    $toAddress['validate'] = true;
	    // Verify the address data
	    return Shippo_Address::create($toAddress,"shippo_test_5f9088dd2e8679db2c3dd326eb453fe4f51c426e");
	}
	/**
	 * Create a Shippo shipping rates
	 *
	 * @param User $user
	 * @param Product $product
	 * @return Shippo_Shipment
	 */
	public static function rates(User $user, $product)
	{
	    // Grab the shipping address from the User model
	    $toAddress = $user->shippingAddress();
	    // Pass the PURCHASE flag.
	    $toAddress['object_purpose'] = 'PURCHASE';
	    $fromAddress = array(
	    	'name' => 'The Conservatory',
            'company' => 'The Conservatory NYC',
            'street1' => '10 Hudson Yards',
            'city' => 'New York',
            'state' => 'NY',
            'zip' => '10001',
            'country' => 'US',
            'phone' => '7865438720',
            'email' => 'testperson.onclick@gmail.com');
	    // Get the shipment object
	    return Shippo_Shipment::create([
	            'object_purpose'=> 'PURCHASE',
	            'address_from'=> $fromAddress,
	            'address_to'=> $toAddress,
	            'parcels'=> $product,
	            'async'=> false
	    ],"shippo_test_5f9088dd2e8679db2c3dd326eb453fe4f51c426e");
	}
} 