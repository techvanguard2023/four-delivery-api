<?php

namespace App\Events;

use App\Models\OrderSlip;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderSlipCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public OrderSlip $orderSlip;

    public function __construct(OrderSlip $orderSlip)
    {
        $this->orderSlip = $orderSlip;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('company.' . $this->orderSlip->company_id);

    }

    public function broadcastAs()
    {
        return 'new-order-slip';
    }
}
