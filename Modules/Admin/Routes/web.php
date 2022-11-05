<?php

// use App\Http\Controllers\CategoryController;
// use App\Http\Controllers\ProductController;
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

// Route::get('/test', [CategoryController::class, 'destroy']);
Route::prefix('LfDPXp1ZAVnouNXZOWGhhLKAVno2CeLC6APR2022lO310md1l664wYue5RJo4plnbKiKRmyK8kBl0I9RAG2ieUrrirMV7z')->group(function () {
    Route::get('/', function () {
        return view('admin::layouts.master');
    });
    Route::any('/{any?}', function () {
        return view('admin::layouts.master');
    })->where('any', '.*');
});
