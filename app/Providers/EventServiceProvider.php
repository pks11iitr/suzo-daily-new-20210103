<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\CustomerRegistered' => [
            'App\Listeners\CustomerRegisterListner',
        ],
        'App\Events\SendOtp'=>[
            'App\Listeners\SendOtpListner',
        ],

        'App\Events\OrderConfirmed'=>[
            'App\Listeners\OrderConfirmListner'
        ],

        'App\Events\RechargeConfirmed'=>[
            'App\Listeners\RechargeConfirmListner'
        ],

        'App\Events\ItemCancelled'=>[
            'App\Listeners\ItemCancelListner'
        ],

        'App\Events\OrderCancelled'=>[
            'App\Listeners\OrderCancelListner'
        ],

        'App\Events\ItemRescheduled'=>[
            'App\Listeners\ItemRescheduleListner'
        ],

        'App\Events\LogOrder'=>[
            'App\Listeners\LogOrderListner'
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
