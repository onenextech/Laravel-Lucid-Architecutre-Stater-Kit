<?php

use App\Services\ApplicationService\Http\Controllers\ApplicationServiceController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'application_services', 'middleware' => 'auth'], function () {
    Route::resource('/', ApplicationServiceController::class)
        ->parameters(['' => 'id'])->only('index', 'show', 'update');
});
