<?php

use App\Http\Controllers\ChannelController;
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

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group([
        'prefix' => 'channel',
    ], function () {
        // Get a current subscriber count of the given channel
        Route::get('/{channel}/subscriber/count', [ChannelController::class, 'subscriberCount']);

        // Get the subscriber history of the given channel
        Route::get('/{channel}/subscriber/history', [ChannelController::class, 'subscriberHistory']);
    });
});
