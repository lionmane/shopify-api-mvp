<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function shippingAddress()
    {
        // return [
        //     'name' => $this->name,
        //     'company' => $this->company,
        //     'street1' => $this->street1,
        //     'city' => $this->city,
        //     'state' => $this->state,
        //     'zip' => $this->zip,
        //     'country' => $this->country,
        //     'phone' => $this->phone,
        //     'email' => $this->email,
        // ];
        return [    
            'name' => 'Aakash Bansal',
            'company' => 'Test Company',
            'street1' => '238 1st Ave',
            'city' => 'New York',
            'state' => 'NY',
            'zip' => '10009',
            'country' => 'US',
            'phone' => '9876543221',
            'email' => 'abansal2107@gmail.com'
            ];
    }
}
