<?php

namespace App\Console\Commands;

use App\Jobs\SyncPlayers as SyncPlayersJob;

use Illuminate\Console\Command;

class SyncPlayers extends Command
{
    /**
     * @var string
     */
    protected $signature = 'sync:players';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @return void
     */
    public function handle()
    {
        SyncPlayersJob::dispatch();
    }
}
