<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'application_service'], function() {

    Route::get('/', function() {
        return response()->json(['path' => '/api/application_service']);
    });

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

});
