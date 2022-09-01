<?php

use App\Services\Authorization\Http\Controllers\PermissionController;
use App\Services\Authorization\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'authorization'], function () {
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:manage-roles');
    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:manage-permissions');
});
