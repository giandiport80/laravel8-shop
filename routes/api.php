<?php

use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::middleware('client')->group(function(){
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{sku}', [ProductController::class, 'show']);
});

Route::middleware('auth:api', 'session')->group(function(){
    Route::get('profile', [UserController::class, 'profile']);
    Route::get('logout', [UserController::class, 'logout']);

    Route::get('carts', [CartController::class, 'index']);
    Route::post('carts', [CartController::class, 'store']);
    Route::patch('carts/{cart_id}', [CartController::class, 'update']);
    Route::delete('carts', [CartController::class, 'clear']);

    Route::get('shipping-options', [CartController::class, 'shippingOptions']);
    Route::post('set-shipping', [CartController::class, 'setShipping']);

    Route::post('orders/checkout', [OrderController::class, 'doCheckout']);
});;









// h: DOKUMENTASI

// library laravelshoppingcart membutuhkan session pada saat kita buat api
// jadi kita perlu memodifikasi route api nya, karena session otomatis men disable session
// kita harus aktifkan session agar library nya bisa berjalan
// pada api, kita harus login dulu sebelum menambahkan barang ke keranjang
// ini karena kita butuh user_id untuk identifikasi session key bahwa item dalam shopping cart punya user tsb

// disini kita menerapkan middleware session yang kita buat sendiri
// tujuannya untuk mengaktifkan session pada cart / keranjang belanja
