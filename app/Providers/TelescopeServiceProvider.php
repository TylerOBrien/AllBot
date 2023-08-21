<?php

namespace App\Providers;

use App\Exceptions\Api\v1\Auth\Forbidden;

use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->hideSensitiveRequestDetails();

        Telescope::avatar(function ($id, $email) {
            return [
                'id' => $id,
                'email' => $email['value'] ?? null,
            ];
        });

        Telescope::filter(function (IncomingEntry $entry) {
            switch ($entry->content['method'] ?? null) {
            case 'HEAD':
            case 'OPTIONS':
                return false;
            }

            if (($entry->content['response_status'] ?? null) === 404) {
                if (! isset($entry->content['authorization'])) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Configure the Telescope authorization services.
     *
     * @return void
     */
    protected function authorization()
    {
        if (app()->runningInConsole() || app()->environment('local')) {
            return;
        }

        if (substr(request()->path(), 0, 9) !== 'telescope') {
            return;
        }

        if (config('telescope.secret') && request()->cookie('telescope') === config('telescope.secret')) {
            return;
        }

        throw new Forbidden('view', 'telescope');
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters([
            'secret.value',
            'password_current',
            'current_password',
            '_token',
        ]);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }
}
