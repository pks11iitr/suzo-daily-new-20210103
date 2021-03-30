<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table='membership_subscriptions';

    protected $fillable=['refid', 'plan_id', 'user_id', 'razorpay_order_id', 'razorpay_order_id_response', 'razorpay_payment_id', 'razorpay_payment_id_response', 'is_confirmed'];


    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }


}
