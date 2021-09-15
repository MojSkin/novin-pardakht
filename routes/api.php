<?php

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

Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('/books')->group(function() {
        Route::post('list', [App\Http\Controllers\BookController::class, 'list']);
        Route::post('store', [App\Http\Controllers\BookController::class, 'store']);
        Route::post('edit', [App\Http\Controllers\BookController::class, 'edit']);
        Route::post('destroy', [App\Http\Controllers\BookController::class, 'destroy']);
    });

    Route::prefix('/authors')->group(function() {
        Route::post('list', [App\Http\Controllers\AuthorController::class, 'list']);
        Route::post('store', [App\Http\Controllers\AuthorController::class, 'store']);
        Route::post('edit', [App\Http\Controllers\AuthorController::class, 'edit']);
        Route::post('destroy', [App\Http\Controllers\AuthorController::class, 'destroy']);
    });
});
