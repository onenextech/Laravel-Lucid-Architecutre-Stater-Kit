<?php

use Illuminate\Support\Facades\Route;
use App\Services\Authorization\Http\Controllers\RoleController;
use App\Services\Authorization\Http\Controllers\PermissionController;

Route::group(['prefix' => 'authorization'], function () {
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:manage-roles');
    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:manage-permissions');
});
