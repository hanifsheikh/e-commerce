<?php


use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\CustomerAuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\SellerController;
use Modules\Admin\Http\Controllers\HomePageController as AdminHomePageController;
use Modules\Admin\Http\Controllers\OrderController as AdminOrderController;
use Modules\Admin\Http\Controllers\SaleController as AdminSaleController;
use Modules\Admin\Http\Controllers\PaymentController as AdminPaymentController;
use Modules\Admin\Http\Controllers\CounterController as AdminCounterController;
use Modules\Admin\Http\Controllers\DashboardController as AdminDashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductImageController;

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

Route::group(['middleware' => ['auth:sanctum', 'auth.admin'], 'prefix' => 'LfDPXp1ZAVnouNXZOWGhhLKAVno2CeLC6APR2022lO310md1l664wYue5RJo4plnbKiKRmyK8kBl0I9RAG2ieUrrirMV7z'], function () {

    // Get Admin Photo
    Route::get('/getAdminPhoto', [AdminAuthController::class, 'getAdminPhoto']);

    // Upload Avatar 
    Route::post('/uploadAvatar', [AdminAuthController::class, 'uploadAvatar']);
    //Admin Settings
    Route::post('/updateSettings', [AdminAuthController::class, 'updateSettings']);
    Route::post('/updateAdminSettingsWithoutPassword', [AdminAuthController::class, 'updateAdminSettingsWithoutPassword']);

    //Change Admin Panel Theme
    Route::post('/changeTheme', [AdminAuthController::class, 'changeTheme']);

    //Sale Report
    Route::get('/refreshCache', [AdminDashboardController::class, 'refreshCache']);
    //Sale Report
    Route::get('/saleGraphData', [AdminSaleController::class, 'fetchData']);

    //fetch sales table data
    Route::post('/sales/fetchTableData', [AdminSaleController::class, 'fetchTableData']);

    //fetch payment table data
    Route::post('/payment/create', [AdminPaymentController::class, 'store']);
    Route::post('/payments/fetchTableData', [AdminPaymentController::class, 'fetchTableData']);
    Route::get('/payment/fetchSellersWithPaymentData/{month}', [AdminPaymentController::class, 'fetchSellersWithPaymentData']);
    Route::post('/payment/searchSellersWithPaymentData', [AdminPaymentController::class, 'searchSellersWithPaymentData']);

    //Counter
    Route::get('/unreadCounter', [AdminCounterController::class, 'unread_counter'])->name('unread-counter');

    //Dashboard Data
    Route::get('/fetchDashboardData', [AdminDashboardController::class, 'fetchData']);


    //Products
    Route::get('/fetchProducts', [ProductController::class, 'fetchProducts'])->name('products');
    Route::post('/fetchRelatedProducts', [ProductController::class, 'fetchRelatedProducts']);
    Route::post('/product/setOffer', [ProductController::class, 'setOffer']);
    Route::post('/product/removeOffer', [ProductController::class, 'removeOffer']);
    Route::get('/editProduct/{id}', [ProductController::class, 'edit']);
    Route::post('/searchProducts', [ProductController::class, 'searchProducts']);
    Route::post('/fetchProductVariants', [ProductVariantController::class, 'fetchProductVariants'])->name('products-variants');
    Route::get('/showProductVaraint/{id}', [ProductVariantController::class, 'showProductVaraint']);
    Route::post('/product/store', [ProductController::class, 'store'])->name('products-create');
    Route::post('/product/storeVariant', [ProductController::class, 'storeVariant']);
    Route::post('/product/imageUpload', [ProductImageController::class, 'storeImage'])->name('products-imageUpload');
    Route::post('/product/variant/imageUpload', [ProductImageController::class, 'storeVariantImage'])->name('products-imageUpload');
    Route::post('/product/update', [ProductController::class, 'update'])->name('products-update');
    Route::post('/product/variant/update', [ProductVariantController::class, 'updateVariant'])->name('products-update');
    Route::post('/product/destroy', [ProductController::class, 'destroy'])->name('products-delete');
    Route::post('/product/variant/destroy', [ProductController::class, 'destroyVariant'])->name('product-variant-delete');
    Route::post('/product/searchRelatedProduct', [ProductController::class, 'searchRelatedProduct']);
    Route::post('/product/addRelatedProduct', [ProductController::class, 'addRelatedProduct']);
    Route::post('/product/removeRelatedProduct', [ProductController::class, 'removeRelatedProduct']);

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin-logout');
    Route::get('/fetchPermissions', [RoleController::class, 'fetchPermissions'])->name('permissions');

    //Roles
    Route::get('/fetchRoles', [RoleController::class, 'fetchRoles'])->name('roles');
    Route::post('/role/store', [RoleController::class, 'store'])->name('roles-create');
    Route::post('/role/update', [RoleController::class, 'update'])->name('roles-update');
    Route::post('/role/destroy', [RoleController::class, 'destroy'])->name('roles-delete');

    //Categories
    Route::get('/fetchCategories', [CategoryController::class, 'fetchCategories'])->name('categories');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('categories-create');
    Route::post('/category/update', [CategoryController::class, 'update'])->name('categories-update');
    Route::post('/category/destroy', [CategoryController::class, 'destroy'])->name('categories-delete');

    //Brands
    Route::get('/fetchBrands', [BrandController::class, 'fetchBrands'])->name('brands');
    Route::post('/searchBrands', [BrandController::class, 'searchBrands']);
    Route::post('/brand/store', [BrandController::class, 'store'])->name('brands-create');
    Route::post('/brand/update', [BrandController::class, 'update'])->name('brands-update');
    Route::post('/brand/destroy', [BrandController::class, 'destroy'])->name('brands-delete');

    //Offers
    Route::get('/fetchOffers', [OfferController::class, 'fetchOffers'])->name('offers');
    Route::post('/searchOffers', [OfferController::class, 'searchOffers']);
    Route::post('/offer/store', [OfferController::class, 'store'])->name('offers-create');
    Route::post('/offer/update', [OfferController::class, 'update'])->name('offers-update');
    Route::post('/offer/destroy', [OfferController::class, 'destroy'])->name('offers-delete');

    //Collections
    Route::get('/fetchCollections', [CollectionController::class, 'fetchCollections'])->name('collections');
    Route::post('/fetchCollectionProducts', [CollectionController::class, 'fetchCollectionProducts'])->name('collection-products');
    Route::post('/searchCollections', [CollectionController::class, 'searchCollections']);
    Route::post('/collection/store', [CollectionController::class, 'store'])->name('collections-create');
    Route::post('/collection/update', [CollectionController::class, 'update'])->name('collections-update');
    Route::post('/collection/destroy', [CollectionController::class, 'destroy'])->name('collections-delete');
    Route::post('/collection/removeProduct', [CollectionController::class, 'removeProduct']);
    Route::post('/collection/addProduct', [CollectionController::class, 'addProduct']);
    Route::post('/collection/searchProduct', [CollectionController::class, 'searchProduct']);

    //Materials
    Route::get('/fetchMaterials', [MaterialController::class, 'fetchMaterials'])->name('collections');
    Route::post('/searchMaterials', [MaterialController::class, 'searchMaterials']);
    Route::post('/material/store', [MaterialController::class, 'store'])->name('materials-create');
    Route::post('/material/update', [MaterialController::class, 'update'])->name('materials-update');
    Route::post('/material/destroy', [MaterialController::class, 'destroy'])->name('materials-delete');


    //Users
    Route::get('/fetchUsers', [UserController::class, 'index'])->name('users');
    Route::post('/searchUsers', [UserController::class, 'searchUsers']);
    Route::post('/user/store', [UserController::class, 'store'])->name('users-create');
    Route::post('/user/update', [UserController::class, 'update'])->name('users-update');
    Route::post('/user/destroy', [UserController::class, 'destroy'])->name('users-delete');

    //Sellers
    Route::get('/fetchSellers', [SellerController::class, 'index'])->name('sellers');
    Route::post('/searchSellers', [SellerController::class, 'searchSellers']);
    Route::post('/seller/store', [SellerController::class, 'store'])->name('sellers-create');
    Route::post('/seller/update', [SellerController::class, 'update'])->name('sellers-update');
    Route::post('/seller/destroy', [SellerController::class, 'destroy'])->name('sellers-delete');
    Route::post('/seller/ban', [SellerController::class, 'ban']);
    Route::post('/seller/banProducts', [SellerController::class, 'banProducts']);
    Route::post('/seller/unban', [SellerController::class, 'unban']);
    Route::post('/seller/approve', [SellerController::class, 'approve']);
    Route::post('/seller/decline', [SellerController::class, 'decline']);
    Route::get('/seller/getSellerPhoto/{id}/', [SellerController::class, 'getSellerPhoto']);
    Route::get('/seller/get_seller_document/{company}/{file}', [SellerController::class, 'download']);

    //Orders
    Route::get('/fetchOrders', [AdminOrderController::class, 'index'])->name('orders');
    Route::post('/searchOrder', [AdminOrderController::class, 'searchOrder']);
    Route::get('/showOrder/{id}', [AdminOrderController::class, 'showOrder'])->name('orders');
    Route::post('/order/updateStatus', [AdminOrderController::class, 'updateItemStatus']);

    //Customers
    Route::get('/fetchCustomers', [CustomerController::class, 'index'])->name('customers');
    Route::post('/searchCustomers', [CustomerController::class, 'searchCustomers']);
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('customers-create');
    Route::post('/customer/update', [CustomerController::class, 'update'])->name('customers-update');
    Route::post('/customer/destroy', [CustomerController::class, 'destroy'])->name('customers-delete');


    //HomePage - Banners
    Route::get('/fetchBanners', [AdminHomePageController::class, 'indexBanner']);
    Route::post('/banner/store', [AdminHomePageController::class, 'storeBanner']);
    Route::post('/banner/bannerPositionUp', [AdminHomePageController::class, 'bannerPositionUp']);
    Route::post('/banner/bannerPositionDown', [AdminHomePageController::class, 'bannerPositionDown']);
    Route::post('/banner/delete', [AdminHomePageController::class, 'bannerDestroy']);

    //HomePage - Recommended Products
    Route::get('/fetchRecommendedProducts', [AdminHomePageController::class, 'indexRecommendedProducts']);
    Route::post('/recommended-product/store', [AdminHomePageController::class, 'storeRecommended']);
    Route::post('/recommended-product/delete', [AdminHomePageController::class, 'recommendedDestroy']);


    Route::post('/get_token_count', [AdminAuthController::class, 'get_token_count']);
    Route::post('/logoutAllSession', [AdminAuthController::class, 'logoutAllSession']);
});

Route::post('/LfDPXp1ZAVnouNXZOWGhhLKAVno2CeLC6APR2022lO310md1l664wYue5RJo4plnbKiKRmyK8kBl0I9RAG2ieUrrirMV7z/login', [AdminAuthController::class, 'login']);
