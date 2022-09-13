<?php

use App\Services\Article\Http\Controllers\ArticleCategoryController;
use App\Services\Article\Http\Controllers\ArticleController;
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

// Prefix: /api/articles
Route::group(['prefix' => 'articles'], function () {
    // Controllers live in src/Services/Article/Http/Controllers
    Route::get('/categories', [ArticleCategoryController::class, 'index']);
    Route::get('/categories/{articleCategoryId}', [ArticleCategoryController::class, 'show']);

    Route::get('/', [ArticleController::class, 'index']);
    Route::get('/{articleId}', [ArticleController::class, 'show']);
});
