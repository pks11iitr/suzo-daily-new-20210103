<?php

namespace App\Listeners;

use App\Events\ItemRescheduled;
use App\Models\Notification;
use App\Services\Notification\FCMNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ItemRescheduleListner
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
     * @param  ItemRescheduled  $event
     * @return void
     */
    public function handle(ItemRescheduled $event)
    {
        $title='Delivery Scheduled';
        $message='Your request to reschedule delivery of '.($event->item->product->name??'').' has been processed';

        Notification::create([
            'user_id'=>$event->item->order->user_id,
            'title'=>$title,
            'description'=>$message,
            'type'=>'individual'
        ]);

        $event->item->order->customer->notify(new FCMNotification($title, $message, ['type'=>'order', 'title'=>$title, 'body'=>$message, 'order_id'=>$event->item->order->id], 'order_details'));
    }
}
