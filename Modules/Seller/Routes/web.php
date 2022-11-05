<?php
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

use App\Http\Controllers\Auth\SellerAuthController;

Route::prefix(env('SELLER_PANEL'))->group(function () {
    Route::get('/', function () {
        return view('seller::layouts.master');
    });
    Route::get('/verify_email/{hash}', [SellerAuthController::class, 'verify'])->middleware('throttle:50,1');

    Route::any('/{any?}', function () {
        return view('seller::layouts.master');
    })->where('any', '.*');
});
