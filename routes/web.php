<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Call api web routes
Route::get('', function (Request $request) {
    $url = $request->getSchemeAndHttpHost();
    return view('home')->with('url_web', $url);
});

Route::get('/login', 'App\Http\Controllers\Controller@show_login')->name('login');
Route::post('/milk/tea/login', 'App\Http\Controllers\Auth\LoginController@login')->name('post_login');
Route::post('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::get('/{address}', 'App\Http\Controllers\Controller@show_web');

Route::prefix('menu')->group(function () {
    Route::get('/products', 'App\Http\Controllers\Controller@show_pagination');
    Route::get('/products/product/{title}', 'App\Http\Controllers\Controller@show_product');
    Route::get('/products/checkout', 'App\Http\Controllers\Controller@show_checkout')->middleware('auth');
});

Route::prefix('user')->group(function () {
    Route::get('/account/{address}', 'App\Http\Controllers\Controller@show_profile')->middleware('auth');
    Route::get('/purchase/order', 'App\Http\Controllers\Controller@show_web_order')->middleware('auth');
    Route::get('/purchase/order/{address}', 'App\Http\Controllers\Controller@show_web_shipping_information')->middleware('auth');
});

Route::prefix('notification')->group(function () {
    Route::get('/{address}', 'App\Http\Controllers\Controller@show_update')->middleware('auth');
});

Route::prefix('vendor')->group(function () {
    Route::get('/home', 'App\Http\Controllers\Controller@show_vendor')->name('home_vendor')->middleware('can:is-vendor');
    Route::prefix('order')->group(function () {
        Route::get('', 'App\Http\Controllers\Controller@show_order')->name('order_vendor')->middleware('auth')->middleware('can:is-vendor');
        Route::get('/detail', 'App\Http\Controllers\Controller@show_order_detail')->middleware('auth')->middleware('can:is-vendor');
    });
    Route::prefix('product')->group(function () {
        Route::get('', 'App\Http\Controllers\Controller@show_product_vendor')->name('product_vendor')->middleware('auth')->middleware('can:is-vendor');
        Route::get('/add/new', 'App\Http\Controllers\Controller@show_add_product')->name('add_product_vendor')->middleware('auth')->middleware('can:is-vendor');
        Route::get('/edit', 'App\Http\Controllers\Controller@show_add_product')->name('edit_product_vendor')->middleware('auth')->middleware('can:is-vendor');
    });
    Route::prefix('finance')->group(function () {
        Route::get('/income', 'App\Http\Controllers\Controller@show_finance_income')->name('income')->middleware('auth')->middleware('can:is-vendor');
        Route::get('/account/balance', 'App\Http\Controllers\Controller@show_finance_balance')->name('income')->middleware('auth')->middleware('can:is-vendor');
    });
    Route::get('/data/sales/analysis', 'App\Http\Controllers\Controller@show_sales_analysis')->middleware('auth')->middleware('can:is-vendor');
    Route::get('/customer/service/chatbot', 'App\Http\Controllers\Controller@chatbot')->middleware('auth')->middleware('can:is-vendor');
    Route::prefix('discount/code')->group(function () {
        Route::get('', 'App\Http\Controllers\Controller@discountCode')->middleware('auth')->middleware('can:is-vendor');
        Route::get('/voucher/new', 'App\Http\Controllers\Controller@addDiscountCode')->middleware('can:is-vendor');
    });
});

Route::prefix('cart')->group(function () {
    Route::get('user', 'App\Http\Controllers\Controller@show_cart')->middleware('auth');
});

Route::get('/test/mk', 'App\Http\Controllers\Controller@show_api')->middleware('auth');
