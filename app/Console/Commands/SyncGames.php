<?php

namespace App\Console\Commands;

use App\Jobs\SyncGames as SyncGamesJob;

use Illuminate\Console\Command;

class SyncGames extends Command
{
    protected $signature = 'sync:games';
    protected $description = '';

    /**
     * @return void
     */
    public function handle()
    {
        SyncGamesJob::dispatch();
    }
}
