<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\LogActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/about', [HomeController::class, 'about'])->name('home.about');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.details');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add_to_cart'])->name('cart.add');
Route::put('/cart/increase-quantity/{rowId}', [CartController::class, 'increase_cart_quantity'])->name('cart.qty.increase');
Route::put('/cart/decrease-quantity/{rowId}', [CartController::class, 'decrease_cart_quantity'])->name('cart.qty.decrease');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove_item'])->name('cart.item.remove');

Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/place-an-order', [CartController::class, 'place_an_order'])->name('cart.place.an.order');
Route::get('/order-confirmation', [CartController::class, 'order_confirmation'])->name('cart.order.confirmation');

Route::middleware('auth')->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
});

Route::get('/admin/login', [AdminController::class, 'adminLoginForm'])->name('admin.login.form');
Route::post('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login');
Route::post('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');


Route::middleware(['auth:admin', CheckRole::class . ':super-admin,admin', LogActivity::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/product/add', [AdminController::class, 'product_add'])->name('admin.product.add');
    Route::get('/admin/product/{id}/view', [AdminController::class, 'product_view'])->name('admin.product.view');
    Route::post('/admin/product/store', [AdminController::class, 'product_store'])->name('admin.product.store');
    Route::get('/admin/product/{id}/edit', [AdminController::class, 'product_edit'])->name('admin.product.edit');
    Route::put('/admin/product/update', [AdminController::class, 'product_update'])->name('admin.product.update');
    Route::delete('/admin/product/{id}/delete', [AdminController::class, 'product_delete'])->name('admin.product.delete');

    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/order/{order_id}/details', [AdminController::class, 'order_details'])->name('admin.order.details');
    Route::put('/admin/order/update-status', [AdminController::class, 'update_order_status'])->name('admin.order.status.update');
    Route::get('/export-orders', [CartController::class, 'export'])->name('export.orders');

    Route::get('/admin/about/edit', [AdminController::class, 'edit_about'])->name('about.edit');
    Route::put('/admin/about/update', [AdminController::class, 'update_about'])->name('about.update');
});

Route::middleware(['auth:admin', CheckRole::class . ':super-admin'])->group(
    function () {
        Route::get('/admin/admins', [UserController::class, 'admins'])->name('admin.admins');
        Route::get('/admin/admin/add', [UserController::class, 'admin_add'])->name('admin.admins.add');
        Route::post('/admin/admin/store', [UserController::class, 'admin_store'])->name('admin.admins.store');
        Route::get('/admin/admin/{id}/edit', [UserController::class, 'admin_edit'])->name('admin.admins.edit');
        Route::put('/admin/admin/update', [UserController::class, 'admin_update'])->name('admin.admins.update');
        Route::delete('/admin/admin/{id}/delete', [UserController::class, 'admin_delete'])->name('admin.admins.delete');

        Route::get('/admin/activity-logs', [UserController::class, 'viewActivityLogs'])->name('admin.activity-logs');


    }
);
