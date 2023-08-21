<?php

namespace App\Events\Api\v1\Cheer;

use App\Models\Cheer;

use Illuminate\Broadcasting\{ InteractsWithSockets, PrivateChannel };
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheerCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The instance of the stream that was created.
     *
     * @var \App\Models\Cheer
     */
    public $cheer;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Cheer  $cheer  The instance of the cheer that was created.
     *
     * @return void
     */
    public function __construct(Cheer $cheer)
    {
        $this->cheer = $cheer;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Stream.' . $this->cheer->stream_id);
    }
}
