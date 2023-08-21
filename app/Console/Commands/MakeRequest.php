<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeRequest extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:croft-request {name} {--ability=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create requests.';

    /**
     * @return string
     */
    protected function buildClass($name)
    {
        $name = trim($this->argument('name'));
        $replace = [];

        $replace['DummyBinding'] = Str::snake($name);
        $replace['DummyRequest'] = $this->getRequestName();
        $replace['DummyModel'] = Str::snake($name);

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name),
        );
    }

    /**
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return "$rootNamespace\\Http\\Requests\\Api\\v1\\" . trim($this->argument('name'));
    }

    /**
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name) . '.php';
        $name = str_replace(
            "{$this->argument('name')}.php",
            "{$this->getRequestName()}.php",
            $name,
        );

        return "{$this->laravel['path']}/" . str_replace('\\', '/', $name);
    }

    /**
     * @return string
     */
    protected function getRequestName()
    {
        return ucfirst($this->option('ability')) . trim($this->argument('name'));
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . "/stubs/requests/{$this->option('ability')}.stub";
    }
}
