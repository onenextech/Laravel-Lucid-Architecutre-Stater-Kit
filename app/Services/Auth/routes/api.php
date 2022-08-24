<?php

use App\Services\Auth\Http\Controllers\AuthController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
});
