<?php

namespace App\Events;

use App\Models\Order;
use App\Models\RescheduleRequest;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RescheduleConfirmed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order, $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order, $user)
    {
        $this->order=$order;
        $this->user=$user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
