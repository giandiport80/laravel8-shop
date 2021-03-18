<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController as ControllersProductController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', [HomeController::class, 'index']);

// product front
Route::get('products', [ControllersProductController::class, 'index']);
Route::get('product/{slug}', [ControllersProductController::class, 'show']);

// favorite
Route::resource('favorites', FavoriteController::class)->only(['index', 'store', 'destroy']);

// cart
Route::get('carts', [CartController::class, 'index'])->name('carts.index');
Route::get('carts/remove/{cartID}', [CartController::class, 'destroy'])->name('carts.destroy');
Route::post('carts', [CartController::class, 'store'])->name('carts.store');
Route::post('carts/update', [CartController::class, 'update'])->name('cart.update');

// order
Route::get('orders', [OrderController::class, 'index']);
Route::get('orders/checkout', [OrderController::class, 'checkout']);
Route::post('orders/checkout', [OrderController::class, 'doCheckout']);
Route::post('orders/shipping-cost', [OrderController::class, 'shippingCost']);
Route::post('orders/set-shipping', [OrderController::class, 'setShipping']);
Route::get('orders/received/{orderID}', [OrderController::class, 'received']);
Route::get('orders/invoice', [OrderController::class, 'invoice']);
Route::get('orders/cities', [OrderController::class, 'cities']);
Route::get('orders/{id}', [OrderController::class, 'show']);

// payment
Route::post('payments/notification', [PaymentController::class, 'notification']);
Route::get('payments/completed', [PaymentController::class, 'completed']);
Route::get('payments/failed', [PaymentController::class, 'failed']);
Route::get('payments/unfinish', [PaymentController::class, 'unfinish']);

Route::get('profile', [ProfileController::class, 'index']);
Route::patch('profile', [ProfileController::class, 'update']);

Route::middleware('auth')->prefix('admin')->group(function(){

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // categories
    Route::resource('categories', CategoryController::class)->except('show');

    // products
    Route::resource('products', ProductController::class);

    // product images
    Route::get('products/{productID}/images', [ProductController::class, 'images'])->name('products.images');
    Route::get('products/{productID}/add-image', [ProductController::class, 'addImage'])->name('products.add_image');
    Route::post('products/images/{productID}', [ProductController::class, 'uploadImage'])->name('products.upload_image');
    Route::delete('products/images/{imageID}', [ProductController::class, 'removeImage'])->name('products.remove_image');

    // attributes
    Route::resource('attributes', AttributeController::class)->except('show');

    // attributeOptions
    Route::get('attributes/{attributeID}/options', [AttributeController::class, 'options'])->name('attributes.options');
    Route::get('attributes/{attributeID}/add-option', [AttributeController::class, 'add_option'])->name('attributes.add_option');
    Route::post('attributes/options/{attributeID}', [AttributeController::class, 'store_option'])->name('store_option');
    Route::delete('attributes/options/{optionID}', [AttributeController::class, 'remove_option'])->name('remove_option');
    Route::get('attributes/options/{optionID}/edit', [AttributeController::class, 'edit_option'])->name('edit_option');
    Route::put('attributes/options/{optionID}', [AttributeController::class, 'update_option'])->name('update_option');

    // users
    Route::resource('users', UserController::class)->except('show');
    // roles
    Route::resource('roles', RoleController::class)->only(['index', 'store', 'update']);

    // order
    Route::get('orders/trashed', [AdminOrderController::class, 'trashed']);
    Route::get('orders/restore/{orderID}', [AdminOrderController::class, 'restore']);
    Route::get('orders/{orderID}/cancel', [AdminOrderController::class, 'cancel']);
    Route::put('orders/cancel/{orderID}', [AdminOrderController::class, 'doCancel']);
    Route::post('orders/complete/{orderID}', [AdminOrderController::class, 'doComplete']);
    Route::resource('orders', AdminOrderController::class);

    // shipment
    Route::resource('shipments', ShipmentController::class);

    // slides
    Route::resource('slides', SlideController::class);
    Route::get('slides/{slideID}/up', [SlideController::class, 'moveUp']);
    Route::get('slides/{slideID}/down', [SlideController::class, 'moveDown']);

    // reports
    Route::get('reports/revenue', [ReportController::class, 'revenue']);
    Route::get('reports/product', [ReportController::class, 'product']);
    Route::get('reports/inventory', [ReportController::class, 'inventory']);
    Route::get('reports/payment', [ReportController::class, 'payment']);

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
