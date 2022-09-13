<?php

use App\Services\Comment\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Service - API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for this service.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Prefix: /api/comment
Route::group(['prefix' => 'comments'], function () {
    // Controllers live in src/Services/Comment/Http/Controllers

    Route::post('/', [CommentController::class, 'create']);
});
