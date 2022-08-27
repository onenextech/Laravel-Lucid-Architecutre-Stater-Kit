<?php

namespace App\Providers;

use App\Services\ApplicationService\Providers\ApplicationServiceServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLucidApplicationProviders();
        //$this->configurePassport();
        $this->configureDevelopmentPackages();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

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
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }


    private function configurePassport()
    {
        //Passport::tokensExpireIn(now()->addDays(15));
        //Passport::refreshTokensExpireIn(now()->addDays(30));
        //Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}
