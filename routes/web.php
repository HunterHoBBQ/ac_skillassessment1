<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BidController;


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

Route::post('/bid', [BidController::class, 'create']);

Route::get('/bid-form', function () {
    return view('bid-form');
});
