<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('cart')->group(function () {
    Route::get('/decode_token', 'App\Http\Controllers\Api\CartController@decodeTokenCart');
    // Route::get('/customer', 'App\Http\Controllers\Api\CartController@show_cart');
    Route::post('', 'App\Http\Controllers\Api\CartController@store');
    Route::post('/token', 'App\Http\Controllers\Api\CartController@createTokenCart');
    Route::delete('/{id}', 'App\Http\Controllers\Api\CartController@destroy');

});

Route::prefix('user')->group(function () {
    Route::get('', 'App\Http\Controllers\Api\UserController@index');
    Route::get('/profile/{id}', 'App\Http\Controllers\Api\UserController@show') ;
    Route::post('', 'App\Http\Controllers\Api\UserController@store');
    Route::put('/{id}', 'App\Http\Controllers\Api\UserController@update');
    Route::put('/password/{id}', 'App\Http\Controllers\Api\UserController@update_password');
});

Route::prefix('product')->group(function () {
    Route::get('/{id}', 'App\Http\Controllers\Api\ProductController@show');
    Route::get('/', 'App\Http\Controllers\Api\ProductController@index');
    Route::post('/', 'App\Http\Controllers\Api\ProductController@store');
    Route::put('/{id}', 'App\Http\Controllers\Api\ProductController@update');
});

Route::prefix('order')->group(function () {
    Route::get('/decode_token', 'App\Http\Controllers\Api\OrderController@decodeTokenOrder');
    Route::post('/token', 'App\Http\Controllers\Api\OrderController@createTokenOrder');
    Route::post('', 'App\Http\Controllers\Api\OrderController@store');
});

Route::prefix('shipping')->group(function () {
    Route::put('/{id}', 'App\Http\Controllers\Api\ShippingController@update_delivery_person');
    Route::put('/status/order/{id}', 'App\Http\Controllers\Api\ShippingController@update_status_by_order_id');
    Route::put('/status/{id}', 'App\Http\Controllers\Api\ShippingController@update_delivery_person');
});

Route::prefix('notification')->group(function () {
    Route::get('/update/order/{id}', 'App\Http\Controllers\Api\NotificationController@show_update_order');
});

Route::prefix('product/view')->group(function () {
    Route::post('', 'App\Http\Controllers\Api\ProductViewController@store')->name('insert_product_view');
});

Route::prefix('/product/size/flavor')->group(function () {
    Route::post('/', 'App\Http\Controllers\Api\ProductSizeFlavorController@store');
    Route::delete('/{id}', 'App\Http\Controllers\Api\ProductSizeFlavorController@destroy');
});

Route::prefix('/product/image')->group(function () {
    Route::post('/', 'App\Http\Controllers\Api\ProductImageController@store');
    // thay delete == post để gửi 1 yêu cầu thay vì gửi nhiều yêu cầu xóa 1 lúc
    Route::post('/delete', 'App\Http\Controllers\Api\ProductImageController@delete')->name('delete');
});

Route::prefix('/vendor')->group(function () {
    Route::prefix('order')->group(function () {
        Route::put('/search/{vendor_id}', 'App\Http\Controllers\Api\VendorController@show_orders_vendor');
        Route::put('/{vendor_id}', 'App\Http\Controllers\Api\VendorController@????');
        ///////////////// ?????? chỉnh sửa trạng thái sang hủy thay vì xóa
    });
    Route::prefix('product')->group(function () {
        Route::get('category/{type}', 'App\Http\Controllers\Api\VendorController@get');
        Route::put('/search/{vendor_id}', 'App\Http\Controllers\Api\VendorController@show_products_vendor');
        Route::put('/{vendor_id}', 'App\Http\Controllers\Api\ProductController@update');
    });
    Route::prefix('finance')->group(function () {
        Route::post('/income', 'App\Http\Controllers\Api\VendorController@finance');
        Route::post('/account/balance', 'App\Http\Controllers\Api\VendorController@account_balance');
    });

    Route::prefix('sales/analysis')->group(function () {
        Route::post('/get/by/date/{shop_id}', 'App\Http\Controllers\Api\VendorController@totalDataSalesAnalysis');
        Route::post('/get/rank/{shop_id}', 'App\Http\Controllers\Api\VendorController@ranking');
    });
});


Route::get('test', 'App\Http\Controllers\Api\VendorController@show_products_vendor');
Route::get('test/test', 'App\Http\Controllers\Api\VendorController@finance');
