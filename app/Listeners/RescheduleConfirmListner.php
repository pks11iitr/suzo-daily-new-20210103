<?php

namespace App\Listeners;

use App\Events\RescheduleConfirmed;
use App\Models\Notification;
use App\Services\Notification\FCMNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RescheduleConfirmListner
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
     * @param  RescheduleConfirmed  $event
     * @return void
     */
    public function handle(RescheduleConfirmed $event)
    {
        $order=$event->order;
        $user=$event->user;

        $message='Your Booking Reschedule Request Has Been Approved';

        Notification::create([
            'user_id'=>$user->id,
            'title'=>'Reschedule Confirmed',
            'description'=>$message,
            'data'=>null,
            'type'=>'individual'
        ]);

        FCMNotification::sendNotification($user->notification_token, 'Reschedule Confirmed', $message);
    }
}
