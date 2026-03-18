<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StoresController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\WholesalerController;
use App\Http\Controllers\Api\RolePermissionController;
use App\Http\Controllers\Api\BannerController as ApiBannerController;
use App\Http\Controllers\Api\SocialController;
use App\Http\Controllers\Api\StoreManagerSignController;

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


Route::group(['namespace' => 'api'], function () {
    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    Route::get('notification', [AuthController::class,'notification']);
    Route::post('forgotPassword', [AuthController::class,'forgotPassword']);
    Route::post('resetPassword', [AuthController::class,'changePassword']);
    Route::post('confirmToken', [AuthController::class,'confirmToken']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', [AuthController::class,'logout']);
        Route::get('/stores', [StoresController::class, 'getStores']);
        Route::post('/selectStore', [StoresController::class, 'selectStore']);
        // Route::get('logout', 'AuthController@logout');

        Route::get('/banner', [ApiBannerController::class, 'index']);
        Route::get('/wholesalers/{storeManagerId}/{storeId}', [WholesalerController::class, 'wholesalers']);
        Route::get('/departments/{vendorId}/{storeManagerId}/{storeId}', [WholesalerController::class, 'departments']);
        Route::get('/products/{vendorId}/{id}/{storeManagerId}/{storeId}', [WholesalerController::class, 'products']);
        Route::get('/getProducts/{vendorId}/{departmentId}/{productId}/{storeManagerId}/{storeId}', [WholesalerController::class, 'getProducts']);
        Route::get('/getVendorsForProduct/{productId}/{storeManagerId}/{storeId}/{excludedVendorId}', [WholesalerController::class, 'getVendorsForProduct']);
        Route::get('/search', [WholesalerController::class, 'search']);


        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/myOrders/{storeManagerId}/{storeId}', [OrderController::class, 'index']);
        Route::get('/saveOrders/{storeManagerId}/{storeId}', [OrderController::class, 'saveOrder']);
        Route::get('/reOrders/{storeManagerId}/{storeId}', [OrderController::class, 'getFrequentOrders']);
        Route::post('/orderStatus', [OrderController::class, 'orderStatus']);
        Route::get('/recommendedProduct/{storeManagerId}/{storeId}/{vendorId}', [OrderController::class, 'getRecommendedProduct']);

        Route::post('/addToWishlist', [WishlistController::class, 'addToWishlist']);
        Route::get('/getWishlist/{storeManagerId}/{storeId}', [WishlistController::class, 'getWishlist']);

        Route::get('/email', [AuthController::class, 'getEmail']);

        Route::post('/upload-invoice', [InvoiceController::class, 'upload']);

        Route::get('/logo', [ApiBannerController::class, 'logo']);

        Route::get('/social', [SocialController::class, 'index']);

        Route::get('/productSearch', [WholesalerController::class, 'productSearch']);



    });
});
Route::post('/addPermission', [RolePermissionController::class, 'addPermission']);
Route::post('/managerSignup', [StoreManagerSignController::class, 'saveStoreManagerWithStore']);
