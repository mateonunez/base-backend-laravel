<?php

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

/**
 * Authorization routes
 */
Route::group([
    'prefix' => 'auth',
    'as' => 'auth.'
], function () {
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
});

/**
 * Declares API resources for some entities
 */

Route::apiResources([
    // Just for tests
    '/base' => \App\Http\Controllers\Controller::class,
]);
