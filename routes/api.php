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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/items', [App\Http\Controllers\ItemController::class, 'allItems'])->name('allItems');
Route::get('/items/{id}', [App\Http\Controllers\ItemController::class, 'itemById'])->name('itemById');
Route::post('/items', [App\Http\Controllers\ItemController::class, 'create'])->name('create');
Route::put('/items/{id}', [App\Http\Controllers\ItemController::class, 'update'])->name('update');
Route::delete('/items/{id}', [App\Http\Controllers\ItemController::class, 'destroyById'])->name('destroyById');