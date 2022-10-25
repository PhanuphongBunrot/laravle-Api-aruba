<?php

use App\Http\Controllers\InfoController;
use App\Http\Controllers\PingController;
use App\Http\Controllers\ApiStatusController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TestipController;
use App\Http\Controllers\UpdataMBController;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('status',[StatusController::class,'status']);
Route::get('info',[InfoController::class,'info']);
Route::get('test',[TestipController::class,'test']);
Route::get('search',[UpdataMBController::class,'updata']);

