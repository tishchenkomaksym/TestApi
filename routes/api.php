<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/register', [ AuthController::class, 'register']);
Route::post('/token', [ AuthController::class, 'token']);
Route::group([
    'middleware' => ['auth:sanctum']
], function () {
//    Route::apiResource('article', ArticleController::class)->except(['show', 'update', 'destroy']);
    Route::get('/articles', [ ArticleController::class, 'index'])->name('api.articles');
    Route::get('/article/{slug}', [ ArticleController::class, 'show'])->name('api.show.article');
    Route::post('/article', [ ArticleController::class, 'store'])->name('api.create.article');
    Route::post('/article-comment/{slug}', [ ArticleController::class, 'storeComment'])->name('api.create-comment.article');
    Route::put('/article/{slug}', [ ArticleController::class, 'update'])
        ->middleware('can:update,slug')->name('api.update.article');
    Route::delete('/article/{slug}', [ ArticleController::class, 'destroy'])
        ->middleware('can:delete,slug')->name('api.delete.article');

    Route::get('/authors', [ AuthorController::class, 'index'])->name('api.author');
    Route::get('/author/show', [ AuthorController::class, 'show'])->name('api.show.author');
    Route::post('/author', [ AuthorController::class, 'store'])->name('api.create.author');
    Route::put('/author', [ AuthorController::class, 'update'])->name('api.update.author');
    Route::delete('/author', [ AuthorController::class, 'destroy'])->name('api.delete.author');

});
