<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Order_item;
use App\Models\User_address;
use App\Models\Payment;


class Order extends Model
{
    //
    protected $guarded = [];

    //
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function order_items(){
        return $this->hasMany(Order_item::class);
    }
    public function userAddress(){
        return $this->belongsTo(User_address::class);
    }
    public function payment() {
        return $this->hasOne(Payment::class);
    }
}
