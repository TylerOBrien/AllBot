<?php

namespace App\Console\Commands;

use App\Jobs\SyncRuns as SyncRunsJob;

use Illuminate\Console\Command;

class SyncRuns extends Command
{
    protected $signature = 'sync:runs';
    protected $description = '';

    /**
     * @return void
     */
    public function handle()
    {
        SyncRunsJob::dispatch();
    }
}
