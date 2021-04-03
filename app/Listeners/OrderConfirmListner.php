<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Mail\SendMail;
use App\Models\Notification;
use App\Models\Order;
use App\Services\Notification\FCMNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class OrderConfirmListner
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
     * @param  OrderConfirmed  $event
     * @return void
     */
    public function handle(OrderConfirmed $event)
    {
        $order=$event->order;

        $this->sendNotifications($order);

    }


    public function sendNotifications($order){

        $title='Order Confirmed';
        $message='Congratulations! Your purchase of Rs. '.$order->total_cost.' at Frestr is successful. Order Reference ID: '.$order->refid;


        $user=$order->customer;

        Notification::create([
            'user_id'=>$order->user_id,
            'title'=>$title,
            'description'=>$message,
            'type'=>'individual'
        ]);

        $user->notify(new FCMNotification($title, $message, ['type'=>'order', 'title'=>$title, 'body'=>$message, 'order_id'=>$order->id], 'order_details'));

    }
}
