<?php

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;

use App\Http\Controllers\CustomerShippingAddressController;
use App\Http\Controllers\HomePageController;
use Illuminate\Support\Facades\Auth;
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
use Laravel\Socialite\Facades\Socialite;

Route::get('/', [HomePageController::class, 'main'])->name('home-page');
Route::get('/product_search', [HomePageController::class, 'searchProduct']);
Route::get('/daily-deals', [HomePageController::class, 'dailyDeals']);
Route::get('/sellers', [HomePageController::class, 'showAllSellers']);
Route::get('/brands', [HomePageController::class, 'showAllBrands']);
Route::get('/new-arrivals', [HomePageController::class, 'showNewArrivals']);
Route::get('/collections', [HomePageController::class, 'showAllCollections']);
Route::get('/shop/{shop_slug}', [HomePageController::class, 'viewStore']);
Route::get('/category/{url}', [HomePageController::class, 'showProductsByCategory'])->where('url', '(.*)');
Route::get('/brand/{slug}', [HomePageController::class, 'showProductsByBrand'])->where('url', '(.*)');
Route::get('/collection/{slug}', [HomePageController::class, 'showProductsByCollection'])->where('url', '(.*)');
Route::get('/offer/{slug}', [HomePageController::class, 'showProductsByOffer'])->where('url', '(.*)');
Route::get('/product/{product_url}/{sku}', [HomePageController::class, 'showProduct']);
Route::get('/storage/product_images/{filename}', function ($filename) {
    $path = storage_path('app/public/product_images/' . $filename);
    return response()->file($path);
});
Route::get('/storage/product_images/thumbnails/{filename}', function ($filename) {
    $path = storage_path('app/public/product_images/thumbnails/' . $filename);
    return response()->file($path);
});
Route::get('/storage/collection_images/{filename}', function ($filename) {
    $path = storage_path('app/public/collection_images/' . $filename);
    return response()->file($path);
});
Route::get('/storage/customer_images/{filename}', function ($filename) {
    $path = storage_path('app/public/customer_images/' . $filename);
    return response()->file($path);
});
Route::get('/storage/recommended_product_images/{filename}', function ($filename) {
    $path = storage_path('app/public/recommended_product_images/' . $filename);
    return response()->file($path);
});
Route::get('/storage/banners/{filename}', function ($filename) {
    $path = storage_path('app/public/banners/' . $filename);
    return response()->file($path);
});
Route::get('/storage/category_images/{filename}', function ($filename) {
    $path = storage_path('app/public/category_images/' . $filename);
    return response()->file($path);
});
Route::get('/storage/category_images/thumbnails/{filename}', function ($filename) {
    $path = storage_path('app/public/category_images/thumbnails/' . $filename);
    return response()->file($path);
});
Route::get('/storage/brand_images/{filename}', function ($filename) {
    $path = storage_path('app/public/brand_images/' . $filename);
    return response()->file($path);
});
Route::get('/storage/collection_images/{filename}', function ($filename) {
    $path = storage_path('app/public/collection_images/' . $filename);
    return response()->file($path);
});
Route::get('/storage/store_profile_images/{filename}', function ($filename) {
    $path = storage_path('app/public/store_profile_images/' . $filename);
    return response()->file($path);
});
Route::get('/storage/store_banner_images/{filename}', function ($filename) {
    $path = storage_path('app/public/store_banner_images/' . $filename);
    return response()->file($path);
});
Route::get('/storage/admin_images/{filename}', function ($filename) {
    $path = storage_path('app/public/admin_images/' . $filename);
    return response()->file($path);
});
Route::get('/storage/seller_images/{filename}', function ($filename) {
    $path = storage_path('app/public/seller_images/' . $filename);
    return response()->file($path);
});
Route::get('/storage/theme_textures/{filename}', function ($filename) {
    $path = storage_path('app/public/theme_textures/' . $filename);
    return response()->file($path);
});
Route::get('/storage/customer_images/{filename}', function ($filename) {
    $path = storage_path('app/public/customer_images/' . $filename);
    return response()->file($path);
})->middleware('auth:customer');
Route::post('/order/place', [OrderController::class, 'placeOrder']);


// Social Login (Facebook)
Route::get('/auth/facebook/redirect', [CustomerAuthController::class, 'facebookRedirect']);
Route::get('/auth/facebook/callback', [CustomerAuthController::class, 'loginWithFacebook']);

// Social Login (Google)
Route::get('/auth/google/redirect', [CustomerAuthController::class, 'googleRedirect']);
Route::get('/auth/google/callback', [CustomerAuthController::class, 'loginWithGoogle']);

//Order
Route::post('/order/checkout', [OrderController::class, 'checkout']);
Route::get('/order/shipping-address', [CustomerShippingAddressController::class, 'shippingAddress'])->name('select-shipping-address');
Route::post('/order/shipping-address', [CustomerShippingAddressController::class, 'store']);
Route::post('/shipping-address/delete', [CustomerShippingAddressController::class, 'destroy']);

// Product Sorting
Route::post('/shop/fetchShopData', [HomePageController::class, 'fetchShopData']);
Route::post('/shop/sortProducts', [HomePageController::class, 'shopSortProducts']);

//Subscribe
Route::post('/subscribe', [HomePageController::class, 'subscribe']);
Route::get('/order/downloadInvoice/{invoice_uuid}', [InvoiceController::class, 'download'])->middleware('throttle:5,5');



// Route::any('/{any?}', function () {
//     return view('admin.master');
// })->where('any', '.*');
