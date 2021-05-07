<?php

namespace App\Events;

use App\Models\Bill;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BillPushed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bill;

    public $action;

    /**
     * Create a new event instance.
     *
     * @param $action
     * @param Bill $bill
     */
    public function __construct(Bill $bill, $action)
    {
        $this->bill = $bill;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('billing');
    }

    /**
     * Set the event name
     *
     * @return string
     */
    public function broadcastAs()
    {
        return $this->action = 'remove' ? 'remove' : 'insert';
    }
}
