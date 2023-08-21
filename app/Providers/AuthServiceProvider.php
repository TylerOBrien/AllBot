<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Folder::class => \App\Policies\Api\v1\FolderPolicy::class,
        \App\Models\Image::class => \App\Policies\Api\v1\ImagePolicy::class,
        \App\Models\Sound::class => \App\Policies\Api\v1\SoundPolicy::class,
        \App\Models\Video::class => \App\Policies\Api\v1\VideoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
