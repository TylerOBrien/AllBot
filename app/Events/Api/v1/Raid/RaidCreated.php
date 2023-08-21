<?php

namespace App\Events\Api\v1\Raid;

use App\Models\Raid;

use Illuminate\Broadcasting\{ InteractsWithSockets, PrivateChannel };
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RaidCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The instance of the stream that was created.
     *
     * @var \App\Models\Raid
     */
    public $raid;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Raid  $redeem  The instance of the raid that was created.
     *
     * @return void
     */
    public function __construct(Raid $raid)
    {
        $this->raid = $raid;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Stream.' . $this->raid->stream_id);
    }
}
