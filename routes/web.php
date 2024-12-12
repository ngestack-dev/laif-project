<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
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

Route::get('/search', [HomeController::class, 'search'])->name('home.search');

Route::middleware('auth')->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/account-details', [UserController::class, 'accountDetails'])->name('user.details');
    Route::put('/update-account-details', [UserController::class, 'updateAccountDetails'])->name('user.details.update');
    Route::put('/update-account-password', [UserController::class, 'updatePassword'])->name('user.password.update');

    Route::get('/account-address', [UserController::class, 'address'])->name('user.address');
    Route::post('/add-address', [UserController::class, 'addAddress'])->name('user.address.add');
    Route::get('/edit-address', [UserController::class, 'editAddress'])->name('user.address.edit');
    Route::put('/update-address', [UserController::class, 'updateAddress'])->name('user.address.update');

    Route::get('/account-orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/account-order/{order_id}/details', [UserController::class, 'orderDetails'])->name('user.order.details');
    Route::put('/account-order/{order_id}/cancel', [UserController::class, 'cancelOrder'])->name('user.order.cancel');
    Route::put('/account-order/{order_id}/received', [UserController::class, 'receivedOrder'])->name('user.order.received');

    Route::post('/account-rating', [UserController::class, 'rating'])->name('user.rating.product');
});

Route::get('/admin/login', [AdminController::class, 'adminLoginForm'])->name('admin.login.form');
Route::post('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login');
Route::post('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');


Route::middleware(['auth:admin', CheckRole::class . ':super-admin,admin',])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/product/add', [AdminController::class, 'product_add'])->name('admin.product.add');
    Route::get('/admin/product/{id}/view', [AdminController::class, 'product_view'])->name('admin.product.view');
    Route::post('/admin/product/store', [AdminController::class, 'product_store'])->name('admin.product.store');
    Route::get('/admin/product/{id}/edit', [AdminController::class, 'product_edit'])->name('admin.product.edit');
    Route::put('/admin/product/update', [AdminController::class, 'product_update'])->name('admin.product.update');
    Route::delete('/admin/product/{id}/delete', [AdminController::class, 'product_delete'])->name('admin.product.delete');
    Route::get('/admin/product/search', [AdminController::class, 'searchProduct'])->name('admin.search.product');

    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/order/{order_id}/details', [AdminController::class, 'order_details'])->name('admin.order.details');
    Route::put('/admin/order/update-status', [AdminController::class, 'update_order_status'])->name('admin.order.status.update');
    Route::delete('/admin/order/{order_id}/delete', [AdminController::class, 'orderDelete'])->name('admin.order.delete');
    Route::get('/export-orders-xlsx', [AdminController::class, 'exportOrdersXlsx'])->name('export.orders.xlsx');
    Route::get('/export-orders-csv', [AdminController::class, 'exportOrdersCsv'])->name('export.orders.csv');
    Route::get('/admin/orders/search', [AdminController::class, 'searchOrders'])->name('admin.search.order');    
    Route::get('/admin/offline-orders', [AdminController::class, 'offlineOrders'])->name('admin.offline.orders');
    Route::get('/admin/add-offline-order', [AdminController::class, 'addOfflineOrder'])->name('admin.add.offline.order');
    Route::post('/admin/store-offline-order', [AdminController::class, 'storeOfflineOrder'])->name('admin.store.offline.order');

    Route::delete('/admin/offline-order/{offline_order_id}/delete', [AdminController::class, 'offlineOrderDelete'])->name('admin.offline.order.delete');

    Route::get('/admin/offline-order/{order_id}/details', [AdminController::class, 'offlineOrderDetails'])->name('admin.offline.order.details');
    Route::get('/admin/offline-orders/search', [AdminController::class, 'searchOfflineOrders'])->name('admin.search.offline.order');   
    Route::get('/export-offline-orders-xlsx', [AdminController::class, 'exportOfflineOrdersXlsx'])->name('export.offline.orders.xlsx');
    Route::get('/export-offline-orders-csv', [AdminController::class, 'exportOfflineOrdersCsv'])->name('export.offline.orders.csv');
    
    Route::get('/admin/offline-products', [AdminController::class, 'offlineProducts'])->name('admin.offline.products');
    Route::get('/admin/add-offline-product', [AdminController::class, 'addOfflineProduct'])->name('admin.add.offline.product');
    Route::post('/admin/store-offline-product', [AdminController::class, 'storeOfflineProduct'])->name('admin.store.offline.product');


    Route::get('/admin/about/edit', [AdminController::class, 'edit_about'])->name('about.edit');
    Route::put('/admin/about/update', [AdminController::class, 'update_about'])->name('about.update');
});

Route::middleware(['auth:admin', CheckRole::class . ':super-admin'])->group(
    function () {
        Route::get('/admin/admins', [SuperAdminController::class, 'admins'])->name('admin.admins');
        Route::get('/admin/admin/add', [SuperAdminController::class, 'admin_add'])->name('admin.admins.add');
        Route::post('/admin/admin/store', [SuperAdminController::class, 'admin_store'])->name('admin.admins.store');
        Route::get('/admin/admin/{id}/edit', [SuperAdminController::class, 'admin_edit'])->name('admin.admins.edit');
        Route::put('/admin/admin/update', [SuperAdminController::class, 'admin_update'])->name('admin.admins.update');
        Route::delete('/admin/admin/{id}/delete', [SuperAdminController::class, 'admin_delete'])->name('admin.admins.delete');

        Route::get('/admin/activity-logs', [SuperAdminController::class, 'viewActivityLogs'])->name('admin.activity-logs');

        // Route::get('/admin/search/product' ,[AdminController::class, 'searchProduct'])->name('admin.search.product');

        Route::get('/admin/search/admin', [SuperAdminController::class, 'searchAdmin'])->name('admin.search.admin');
        Route::get('/admin/search/admin-log', [SuperAdminController::class, 'searchAdminLog'])->name('admin.search.admin-log');


    }
);
