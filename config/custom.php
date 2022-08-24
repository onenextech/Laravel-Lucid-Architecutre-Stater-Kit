<?php

return [
    'toggle_app_services' => env('TOGGLE_APP_SERVICES', true),
    'lucid_application_providers' => [
        \App\Services\Auth\Providers\AuthServiceProvider::class,
    ]
];
