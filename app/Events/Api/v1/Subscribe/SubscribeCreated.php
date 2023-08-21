<?php

namespace App\Events\Api\v1\Redeem;

use App\Models\Subscribe;

use Illuminate\Broadcasting\{ InteractsWithSockets, PrivateChannel };
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscribeCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The instance of the stream that was created.
     *
     * @var \App\Models\Susbcribe
     */
    public $subscribe;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Subscribe  $subscribe  The instance of the subscribe that was created.
     *
     * @return void
     */
    public function __construct(Subscribe $subscribe)
    {
        $this->subscribe = $subscribe;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Stream.' . $this->subscribe->stream_id);
    }
}
