<?php

namespace App\Providers;

use App\Helpers\Enum;
use App\Services\ApplicationService\Providers\ApplicationServiceServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeServiceProvider;

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
        $this->registerBlueprintMacros();
        $this->registerCollectionMacros();
    }

    private function registerLucidApplicationProviders()
    {
        if (config('core.toggle_app_services')) {
            $this->app->register(ApplicationServiceServiceProvider::class);
        } else {
            collect(config('core.lucid_application_providers'))
                ->map(fn ($provider) => $provider['provider'])
                ->each(fn ($provider) => $this->app->register($provider));
        }
    }

    private function configureDevelopmentPackages()
    {
        if ($this->app->environment(['local', 'development'])) {
            Telescope::night();
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

    private function registerBlueprintMacros()
    {
        Blueprint::macro('snowflakeId', fn ($column = 'id') => $this->unsignedBigInteger($column));
        Blueprint::macro('snowflakeIdAndPrimary', fn ($column = 'id') => $this->snowflakeId($column)->primary());

        Blueprint::macro('auditColumns', function () {
            $this->snowflakeId('created_by')->nullable();
            $this->snowflakeId('updated_by')->nullable();
            $this->snowflakeId('deleted_by')->nullable();
            $this->timestamps();
            $this->softDeletes();

            return $this;
        });
    }

    private function registerCollectionMacros() {
        Collection::macro('enum', function ()  {
            return Enum::make($this)->enumToCollection();
        });
    }
}
