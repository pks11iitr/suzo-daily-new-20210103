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

        'App\Events\RescheduleConfirmed'=>[
            'App\Listeners\RescheduleConfirmListner'
        ],

        'App\Events\RechargeConfirmed'=>[
            'App\Listeners\RechargeConfirmListner'
        ],
        'App\Events\TherapistRegistered' => [
            'App\Listeners\TherapistRegisterListner',
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
