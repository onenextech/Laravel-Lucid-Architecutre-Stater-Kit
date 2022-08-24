<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'application-services'], function () {
    Route::get('/', function () {
        return view('application_service::welcome');
    });
});

