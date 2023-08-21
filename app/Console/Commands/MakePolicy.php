<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakePolicy extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:croft-policy {name} {--owned}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a policy.';

    /**
     * @return string
     */
    protected function buildClass($name)
    {
        $name = trim($this->argument('name'));
        $replace = [];

        $replace['DummyPolicy'] = $this->getPolicyName();
        $replace['DummyModel'] = $name;
        $replace['DummyBinding'] = Str::snake($name);
        $replace['DummyPluralBinding'] = Str::snake(Str::plural($name));
        $replace['DummyBindingId'] = Str::snake($name) . '_id';

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
        return $rootNamespace . '\Policies\Api\v1';
    }

    /**
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name) . '.php';
        $name = str_replace(
            "{$this->argument('name')}.php",
            "{$this->getPolicyName()}.php",
            $name,
        );

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name);
    }

    /**
     * @return string
     */
    protected function getPolicyName()
    {
        return str_replace('Policy', '', trim($this->argument('name'))) . 'Policy';
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/policies/' . ($this->option('owned') ? 'policy-owned.stub' : 'policy.stub');
    }
}
