<?php

use App\Http\Controllers\ApiapsController;
use App\Http\Controllers\ApiStatusController;
use App\Http\Controllers\ApsController;
use App\Http\Controllers\CheckipController;
use App\Http\Controllers\clientsController;
use App\Http\Controllers\dropController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\IPgroupController;
use App\Http\Controllers\PingController;
use App\Http\Controllers\UpdataMBController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use League\Flysystem\RootViolationException;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('getaps',[ApsController::class,'aps']);
Route ::get('toaps',[ApiapsController::class,'apiaps']);
Route::get('clients',[clientsController::class,'clients']);
Route::get('apistatus',[ApiStatusController::class,'apimaster']);
Route::get('info',[InfoController::class,'info']);
Route::get('ping',[PingController::class,'ping']);
Route::get('ipgroup',[IPgroupController::class,'ipgroup']);
Route::get('check',[CheckipController::class,'Ckeck']);
Route::get('drop',[dropController::class,'drop']);
Route::get('search',[UpdataMBController::class,'updata']);
