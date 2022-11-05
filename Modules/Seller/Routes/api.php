<?php


use App\Http\Controllers\Auth\SellerAuthController;

use App\Http\Controllers\CategoryController;
use Modules\Seller\Http\Controllers\ProductController as ProductController;
use Modules\Seller\Http\Controllers\ProductVariantController as ProductVariantController;
use Modules\Seller\Http\Controllers\ProductImageController as ProductImageController;
use Modules\Seller\Http\Controllers\OrderController as SellerOrderController;
use Modules\Seller\Http\Controllers\WishlistController as SellerWishlistController;
use Modules\Seller\Http\Controllers\SaleController as SellerSaleController;
use Modules\Seller\Http\Controllers\PaymentController as SellerPaymentController;
use Modules\Seller\Http\Controllers\CounterController as SellerCounterController;
use Modules\Seller\Http\Controllers\DashboardController as SellerDashboardController;
use Modules\Seller\Http\Controllers\BrandController as BrandController;
use Modules\Seller\Http\Controllers\OfferController as OfferController;
use App\Http\Controllers\CollectionController;
use Modules\Seller\Http\Controllers\MaterialController as MaterialController;



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

Route::group(['middleware' => ['auth:sanctum', 'auth.seller'], 'prefix' => env('SELLER_PANEL')], function () {

    // Get Seller Photo
    Route::get('/getSellerPhoto', [SellerAuthController::class, 'getSellerPhoto']);

    // Upload Avatar
    Route::post('/uploadAvatar', [SellerAuthController::class, 'uploadAvatar']);
    Route::post('/uploadStoreLogo', [SellerAuthController::class, 'uploadStoreLogo']);
    Route::post('/uploadStoreBanner', [SellerAuthController::class, 'uploadStoreBanner']);

    //Seller Settings
    Route::post('/updateSettings', [SellerAuthController::class, 'updateSettings']);
    Route::post('/updateSellerSettingsWithoutPassword', [SellerAuthController::class, 'updateSellerSettingsWithoutPassword']);
    //Change Seller Panel Theme
    Route::post('/changeTheme', [SellerAuthController::class, 'changeTheme']);

    //Sale Report
    Route::get('/saleGraphData', [SellerSaleController::class, 'fetchData']);

    //fetch sales table data
    Route::post('/sales/fetchTableData', [SellerSaleController::class, 'fetchTableData']);

    //fetch Payments table data
    Route::post('/payments/fetchTableData', [SellerPaymentController::class, 'fetchTableData']);

    //Counter
    Route::get('/unreadCounter', [SellerCounterController::class, 'unread_counter']);

    //Dashboard Data
    Route::get('/fetchDashboardData', [SellerDashboardController::class, 'fetchData']);

    //Products
    Route::get('/fetchProducts', [ProductController::class, 'fetchProducts']);
    Route::get('/fetchShopProducts', [ProductController::class, 'fetchShopProducts']);
    Route::post('/product/setOffer', [ProductController::class, 'setOffer']);
    Route::post('/product/removeOffer', [ProductController::class, 'removeOffer']);
    Route::get('/editProduct/{id}', [ProductController::class, 'edit']);
    Route::post('/searchProducts', [ProductController::class, 'searchProducts']);
    Route::post('/fetchProductVariants', [ProductVariantController::class, 'fetchProductVariants']);
    Route::get('/showProductVaraint/{id}', [ProductVariantController::class, 'showProductVaraint']);
    Route::post('/product/store', [ProductController::class, 'store']);
    Route::post('/product/storeVariant', [ProductController::class, 'storeVariant']);
    Route::post('/product/imageUpload', [ProductImageController::class, 'storeImage']);
    Route::post('/product/variant/imageUpload', [ProductImageController::class, 'storeVariantImage']);
    Route::post('/product/update', [ProductController::class, 'update']);
    Route::post('/product/variant/update', [ProductVariantController::class, 'updateVariant']);
    Route::post('/product/destroy', [ProductController::class, 'destroy']);
    Route::post('/product/variant/destroy', [ProductController::class, 'destroyVariant']);

    //Categories
    Route::get('/fetchCategories', [CategoryController::class, 'fetchCategories']);

    //Brands
    Route::get('/fetchBrands', [BrandController::class, 'fetchBrands']);

    //Offers
    Route::get('/fetchOffers', [OfferController::class, 'fetchOffers']);

    //Collections
    Route::get('/fetchCollections', [CollectionController::class, 'fetchCollections']);

    //Materials
    Route::get('/fetchMaterials', [MaterialController::class, 'fetchMaterials']);
    Route::post('/searchMaterials', [MaterialController::class, 'searchMaterials']);

    //Orders
    Route::get('/fetchOrders', [SellerOrderController::class, 'index']);
    Route::post('/searchOrder', [SellerOrderController::class, 'searchOrder']);
    Route::get('/showOrder/{id}', [SellerOrderController::class, 'showOrder']);
    Route::post('/order/updateStatus', [SellerOrderController::class, 'updateItemStatus']);
    Route::post('/order/downloadInvoice', [SellerOrderController::class, 'downloadInvoice']);
    Route::get('/order/downloadInvoice/{invoice_uuid}', [SellerOrderController::class, 'downloadInvoiceByQRCode']);

    // Wishlist 
    Route::get('/wishlist/fetchProducts', [SellerWishlistController::class, 'index']);
    Route::post('/wishlist/fetchVariants', [SellerWishlistController::class, 'showVariants']);


    Route::post('/get_token_count', [SellerAuthController::class, 'get_token_count']);
    Route::post('/logoutAllSession', [SellerAuthController::class, 'logoutAllSession']);
});

Route::post('/' . env('SELLER_PANEL') . '/logout', [SellerAuthController::class, 'logout'])->middleware(['auth:sanctum', 'auth.sellerinactive']);
Route::post('/' . env('SELLER_PANEL') . '/uploadDocuments', [SellerAuthController::class, 'uploadDocuments'])->middleware(['auth:sanctum', 'auth.sellerinactive']);

Route::post('/' . env('SELLER_PANEL') . '/login', [SellerAuthController::class, 'login']);
Route::post('/' . env('SELLER_PANEL') . '/register', [SellerAuthController::class, 'register']);
