<?php

namespace App\Services\ApplicationService\Providers;

use App\Data\Models\ApplicationService;
use App\Domains\ApplicationService\Jobs\RememberApplicationServiceJob;
use App\Exceptions\UnableToRegisterLucidServicesFromDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;

class ApplicationServiceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap migrations and factories for:
     * - `php artisan migrate` command.
     * - factory() helper.
     *
     * Previous usage:
     * php artisan migrate --path=src/Services/ApplicationService/database/migrations
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

        try {
            if (DB::getSchemaBuilder()->hasTable('application_services')) {
                dispatch_sync(new RememberApplicationServiceJob()  )
                    ->filter(fn($provider) => $provider->active)
                    ->pluck('provider')
                    ->each(fn($provider) => $this->app->register($provider));
            } else {
                // Table does not exist
                if (!$this->app->runningInConsole()) {
                    throw new UnableToRegisterLucidServicesFromDatabase('Toggle Lucid Service config is turned on, but the application_services table does not exist');
                }
            }
        } catch (\Exception $exception) {
            if ($exception instanceof UnableToRegisterLucidServicesFromDatabase) {
                throw $exception;
            }

            // You can debug here
            // info($_);
            throw new UnableToRegisterLucidServicesFromDatabase('Toggle Lucid Service config is turned on and there was something wrong with loading services');
        }
    }

    /**
     * Register the ApplicationService service provider.
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
     * Register the ApplicationService service resource namespaces.
     *
     * @return void
     */
    protected function registerResources()
    {
        // Translation must be registered ahead of adding lang namespaces
        $this->app->register(TranslationServiceProvider::class);

        Lang::addNamespace('application_service', realpath(__DIR__ . '/../resources/lang'));

        View::addNamespace('application_service', base_path('resources/views/vendor/application_service'));
        View::addNamespace('application_service', realpath(__DIR__ . '/../resources/views'));
    }
}
