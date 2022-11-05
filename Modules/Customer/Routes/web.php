<?php

use App\Http\Controllers\Auth\CustomerAuthController;
use  Modules\Customer\Http\Controllers\CustomerController;
use  Modules\Customer\Http\Controllers\OrderController;
use Modules\Customer\Http\Controllers\WishlistController;

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

Route::prefix('customer')->group(function () {
    Route::get('/', function () {
        return redirect('/customer/dashboard');
    });
    Route::get('/dashboard', [CustomerController::class, 'orders'])->middleware('auth:customer');
    Route::get('/orders', [CustomerController::class, 'orders'])->middleware('auth:customer');
    Route::get('/wishlist', [CustomerController::class, 'wishlist'])->middleware('auth:customer');

    Route::post('/registration', [CustomerAuthController::class, 'register']);
    Route::get('/forgot-password', [CustomerAuthController::class, 'forgot_password']);
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login');
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->middleware('auth:customer');
    Route::get('/verify_email/{hash}', [CustomerAuthController::class, 'verify'])->middleware('throttle:50,1');
    Route::get('/email_sent', [CustomerAuthController::class, 'email_sent_notice'])->middleware('throttle:10,1');
    Route::get('/registration', function () {
        return view('customer::register');
    })->middleware('guest:customer')->name('customer-register');
    Route::get('/login', function () {
        return view('customer::login');
    })->middleware('guest:customer')->name('customer-login');




    Route::get('/auth/facebook', [CustomerAuthController::class, 'facebookRedirect']);
    Route::get('/auth/facebook/callback', [CustomerAuthController::class, 'loginWithFacebook']);

    Route::get('/auth/google', [CustomerAuthController::class, 'googleRedirect']);
    Route::get('/auth/google/callback', [CustomerAuthController::class, 'loginWithGoogle']);


    // Orders
    Route::get('/order-details={order_no}', [OrderController::class, 'showOrderDetails']);
    Route::post('/order-item/recieved', [OrderController::class, 'orderItemReceived']);
    Route::post('/fetchOrderDetailsData', [OrderController::class, 'fetchOrderDetailsData']);
    Route::post('/productReview', [OrderController::class, 'productReview']);
    Route::post('/productReview/update', [OrderController::class, 'updateProductReview']);
    Route::post('/order-item/return', [OrderController::class, 'orderItemReturned']);
    Route::post('/order/cancel', [OrderController::class, 'cancelOrder']);
    Route::get('/order/downloadInvoice/{invoice_uuid}', [OrderController::class, 'downloadInvoiceByQRCode']);

    // WishList 
    Route::post('/wishlist/save', [WishlistController::class, 'save']);
    Route::post('/wishlist/delete', [WishlistController::class, 'removeItem']);
    Route::get('/fetchWishListDetailsData', [WishlistController::class, 'fetchWishListDetailsData']);
});
