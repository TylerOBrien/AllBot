<?php

namespace App\Events\Api\v1\Stream;

use App\Models\Stream;

use Illuminate\Broadcasting\{ InteractsWithSockets, PrivateChannel };
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StreamCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The instance of the stream that was created.
     *
     * @var \App\Models\Stream
     */
    public $stream;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Stream  $stream  The instance of the stream that was created.
     *
     * @return void
     */
    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Channel.' . $this->stream->channel_id);
    }
}
