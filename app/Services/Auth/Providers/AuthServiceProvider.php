<?php

namespace App\Services\Auth\Providers;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap migrations and factories for:
     * - `php artisan migrate` command.
     * - factory() helper.
     *
     * Previous usage:
     * php artisan migrate --path=src/Services/Auth/database/migrations
     * Now:
     * php artisan migrate
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom([
            realpath(__DIR__ . '/../database/migrations')
        ]);
    }

    /**
    * Register the Auth service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(BroadcastServiceProvider::class);

        $this->registerResources();
    }

    /**
     * Register the Auth service resource namespaces.
     *
     * @return void
     */
    protected function registerResources()
    {
        // Translation must be registered ahead of adding lang namespaces
        $this->app->register(TranslationServiceProvider::class);

        Lang::addNamespace('auth', realpath(__DIR__.'/../resources/lang'));

        View::addNamespace('auth', base_path('resources/views/vendor/auth'));
        View::addNamespace('auth', realpath(__DIR__.'/../resources/views'));
    }
}
