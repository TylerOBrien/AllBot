<?php

namespace App\Console\Commands;

use App\Support\Time as SupportTime;

use Illuminate\Console\Command;

class Time extends Command
{
    protected $signature = 'time';
    protected $description = '';

    public function handle()
    {
        $period = 'PT15M57.915S';
        $ms = SupportTime::getMilliseconds($period);

        var_dump($period);
        var_dump($ms);
    }
}
