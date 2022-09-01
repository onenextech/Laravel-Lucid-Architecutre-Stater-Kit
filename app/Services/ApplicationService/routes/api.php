<?php

use App\Services\ApplicationService\Http\Controllers\ApplicationServiceController;
use Illuminate\Support\Facades\Route;

Route::resource('/application_services', ApplicationServiceController::class)
    ->only('index', 'show', 'update')
    ->middleware(['role:super-admin']);
