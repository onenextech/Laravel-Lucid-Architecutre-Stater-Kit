<?php

namespace App\Providers;

use App\Services\ApplicationService\Providers\ApplicationServiceServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerLucidApplicationProviders();
        //$this->configurePassport();
        $this->configureDevelopmentPackages();
    }


    public function boot()
    {
        $this->registerMacros();
    }


    private function registerLucidApplicationProviders()
    {
        if (config('core.toggle_app_services')) {
            $this->app->register(ApplicationServiceServiceProvider::class);
        } else {
            collect(config('core.lucid_application_providers'))
                ->map(fn($provider) => $provider['provider'])
                ->each(fn($provider) => $this->app->register($provider));
        }
    }


    private function configureDevelopmentPackages()
    {
        if ($this->app->environment(['local', 'development'])) {
            $this->app->register(TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }


    private function configurePassport()
    {
        //Passport::tokensExpireIn(now()->addDays(15));
        //Passport::refreshTokensExpireIn(now()->addDays(30));
        //Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }


    private function registerMacros()
    {
        Blueprint::macro('snowflakeId', fn($column) => $this->unsignedBigInteger($column));
        Blueprint::macro('snowflakeIdAndPrimary', fn($column) => $this->snowflakeId($column)->primary());
    }
}
