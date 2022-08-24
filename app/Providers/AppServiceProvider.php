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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }


    /**
     * Check if the application needs controllable lucid application providers
     *
     * if true, let the ApplicationServiceServiceProvider register the providers conditionally
     *
     * if not, register all the providers here
     *
     * @return void
     */
    private function registerLucidApplicationProviders() {
        if (config('custom.toggle_app_services')) {
            $this->app->register(ApplicationServiceServiceProvider::class);
        } else {
            collect(config('custom.lucid_application_providers'))
                ->each(fn ($provider) => $this->app->register($provider));
        }
    }
}
