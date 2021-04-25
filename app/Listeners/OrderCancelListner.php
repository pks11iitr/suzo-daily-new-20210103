<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use App\Models\Notification;
use App\Services\Notification\FCMNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderCancelListner
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
     * @param  OrderCancelled  $event
     * @return void
     */
    public function handle(OrderCancelled $event)
    {
        $title='Item Cancelled';
        $message='Your cancellation request for order id: '.($event->order->refid).' has been processed';


        Notification::create([
            'user_id'=>$event->order->user_id,
            'title'=>$title,
            'description'=>$message,
            'type'=>'individual'
        ]);

        $event->order->customer->notify(new FCMNotification($title, $message, ['type'=>'order', 'title'=>$title, 'body'=>$message, 'order_id'=>$event->order->id], 'order_details'));
    }
}
