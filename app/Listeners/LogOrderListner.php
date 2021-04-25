<?php

namespace App\Listeners;

use App\Events\LogOrder;
use App\Models\OrderChange;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogOrderListner
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LogOrder  $event
     * @return void
     */
    public function handle(LogOrder $event)
    {
       OrderChange::create(
           $event->order->only('refid',  'status', 'payment_status', 'payment_mode', 'total_cost','savings', 'coupon', 'coupon_discount', 'delivery_charge', 'use_balance', 'use_points', 'balance_used', 'points_used')
       );
    }
}
