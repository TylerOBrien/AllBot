<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeResource extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:croft-resource {name} {--index}';

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

        $replace['DummyModel'] = $name;
        $replace['DummyBinding'] = Str::snake($name);
        $replace['DummyPluralBinding'] = Str::snake(Str::plural($name));

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
        return "$rootNamespace\\Http\\Resources\\Api\\v1\\{$this->getResourceModelName()}";
    }

    /**
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name) . '.php';
        $name = str_replace(
            "{$this->argument('name')}.php",
            $this->option('index') ? "{$this->getResourceIndexName()}.php" : "{$this->getResourceName()}.php",
            $name,
        );

        return "{$this->laravel['path']}/" . str_replace('\\', '/', $name);
    }

    /**
     * @return string
     */
    protected function getResourceName()
    {
        return "{$this->getResourceModelName()}Resource";
    }

    /**
     * @return string
     */
    protected function getResourceIndexName()
    {
        return "{$this->getResourceModelName()}IndexResource";
    }

    /**
     * @return string
     */
    protected function getResourceModelName()
    {
        return str_replace('Resource', '', trim($this->argument('name')));
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/resources/' . ($this->option('index') ? 'resource-index.stub' : 'resource.stub');
    }
}
