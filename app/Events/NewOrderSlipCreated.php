<?php

namespace App\Events;

use App\Models\OrderSlip;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderSlipCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orderSlip;

    public function __construct($orderSlip)
    {
        $this->orderSlip = $orderSlip;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('company.' . $this->orderSlip->company_id);
    }

    public function broadcastAs(): string
    {
        return 'new.order.slip';
    }

    public function broadcastWith(): array
    {
        return [
            'orderSlip' => $this->orderSlip,
        ];
    }
}
