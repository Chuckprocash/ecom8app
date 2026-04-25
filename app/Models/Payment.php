<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Payment extends Model
{
    //
    protected $guarded = [];

    public function order() {
        return $this->belongsTo(Order::class);
    } 
}
