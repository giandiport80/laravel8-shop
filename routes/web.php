<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController as ControllersProductController;
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

// cart
Route::get('carts', [CartController::class, 'index'])->name('carts.index');
Route::get('carts/remove/{cartID}', [CartController::class, 'destroy'])->name('carts.destroy');
Route::post('carts', [CartController::class, 'store'])->name('carts.store');
Route::post('carts/update', [CartController::class, 'update'])->name('cart.update');

Route::middleware('auth')->prefix('admin')->group(function(){

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // categories
    Route::resource('categories', CategoryController::class);

    // products
    Route::resource('products', ProductController::class);

    // product images
    Route::get('products/{productID}/images', [ProductController::class, 'images'])->name('products.images');
    Route::get('products/{productID}/add-image', [ProductController::class, 'add_image'])->name('products.add_image');
    Route::post('products/images/{productID}', [ProductController::class, 'upload_image'])->name('products.upload_image');
    Route::delete('products/images/{imageID}', [ProductController::class, 'remove_image'])->name('products.remove_image');

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

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
