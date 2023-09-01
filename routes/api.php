<?php


use App\Http\Controllers\FreightController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Logger;

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

Route::group(['prefix' => 'clients', 'middleware' => Logger::class], function () {

    Route::get('freight', [FreightController::class, 'getFreight']);
});