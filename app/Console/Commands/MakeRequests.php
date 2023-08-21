<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakeRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:croft-requests {name} {--ability=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create multiple requests.';

    /**
     * @return int
     */
    public function handle()
    {
        $name = trim($this->argument('name'));

        foreach (explode(',', $this->option('ability')) as $ability) {
            Artisan::call('make:croft-request', ['name' => $name, '--ability' => $ability]);
        }

        return 0;
    }
}
