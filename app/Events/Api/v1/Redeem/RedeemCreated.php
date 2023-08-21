<?php

namespace App\Events\Api\v1\Redeem;

use App\Models\Redeem;

use Illuminate\Broadcasting\{ InteractsWithSockets, PrivateChannel };
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RedeemCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The instance of the stream that was created.
     *
     * @var \App\Models\Redeem
     */
    public $redeem;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Redeem  $redeem  The instance of the redeem that was created.
     *
     * @return void
     */
    public function __construct(Redeem $redeem)
    {
        $this->redeem = $redeem;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Stream.' . $this->redeem->stream_id);
    }
}
