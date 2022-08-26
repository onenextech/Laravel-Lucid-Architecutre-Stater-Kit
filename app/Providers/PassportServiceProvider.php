<?php

namespace App\Providers;

class PassportServiceProvider extends \Laravel\Passport\PassportServiceProvider
{

    /*
     * Laravel Passport doesn't allow us to disable it's oauth route
     * This provider is to override its default nature
     *
     * laravel/passport was added to the don't discovery array in composer.json file to disable auto discovery by Laravel
     * And this custom provider will be registered by AppServiceProvider of the application, manually
     */
    public function boot()
    {
        //Toggle Passport Oauth Routes Here
        //$this->registerRoutes();

        $this->registerResources();
        $this->registerMigrations();
        $this->registerPublishing();
        $this->registerCommands();

        $this->deleteCookieOnLogout();
    }
}
