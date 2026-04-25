<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class User_address extends Model
{
    protected $guarded = [];
    
    protected $fillable = [
        'type',
        'address1',
        'address2',
        'city',
        'state',
        'zipcode',
        'country_code',
        'isMain',
        'user_id',
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }

}
