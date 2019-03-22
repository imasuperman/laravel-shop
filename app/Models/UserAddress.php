<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'province' ,
        'city' ,
        'district' ,
        'address' ,
        'zip' ,
        'contact_name' ,
        'contact_phone' ,
        'last_used_at' ,
    ];
    protected $dates    = [ 'last_used_at' ];

    public function user ()
    {
        return $this->belongsTo( User::class );
    }

    public function getFullAddressAttribute ( $key )
    {
        return "{$this->province}{$this->city}{$this->district}{$this->address}";
    }

}
