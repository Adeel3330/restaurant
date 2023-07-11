<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserControllerWeb;
use App\Http\Controllers\AdminControllerWeb;
use App\Http\Controllers\OrderControllerWeb;
use App\Http\Controllers\BannerControllerWeb;
use App\Http\Controllers\DriverControllerWeb;
use App\Http\Controllers\ProductControllerWeb;
use App\Http\Controllers\CategoryControllerWeb;
use App\Http\Controllers\DeliveryControllerWeb;
use App\Http\Controllers\RestaurantControllerWeb;
use App\Http\Controllers\SubcategoryControllerWeb;
use App\Http\Controllers\RestaurantTimingController;
use App\Http\Controllers\RestaurantOwnerControllerWeb;

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

Route::get('/', function () {
    return view('welcome');
});

Route::view('/admin', 'admin/login');
Route::view('/admin/login', 'admin/login');
// Route::view('/admin/home', '/admin/index');

// Route::get('/admin/logout', [UserControllerWeb::class, 'logout']);
Route::post('/admin/login', [UserControllerWeb::class, 'AdminLogin']);



Route::middleware('AdminWebLogin')->controller(UserControllerWeb::class)->group(function () {
    Route::get('/admin/home', 'dashboard');
    Route::get('/v3/users', 'getusers');
    Route::get('/v3/users/{id}', 'getusers');
    Route::get('/admin/logout','logout');
   
});

Route::middleware('AdminWebLogin')->controller(RestaurantTimingController::class)->group(function () {
    Route::get('/admin/week-create', 'week_days_create');
    Route::get('/admin/week_days', 'week_days');
    Route::get('/admin/week_days/{id}', 'week_days');
    Route::post('/admin/week_day_create', 'week_day_create');
    Route::get('/admin/week-edit/{id}', 'edit_week_day');
    Route::post('/admin/week_day_edit/{id}','week_day_edit');
    Route::get('/admin/delete_week_day/{id}', 'delete_week_day');
});

Route::middleware('AdminWebLogin')->controller(CategoryControllerWeb::class)->group(function () {
    Route::post('/admin/category_create', 'category_create');
    Route::get('/admin/delete_category', 'delete_category');
    Route::get('/admin/delete_category/{id}', 'delete_category');
    Route::post('/admin/edit_category/{id}', 'edit_category');
    Route::get('/admin/category-edit/{id}', 'edit_category_view');
    Route::get('/admin/categories', 'categories');
    Route::get('/admin/categories/{id}', 'categories');
    Route::get('/admin/category-create','category_create_view');

});


Route::middleware('AdminWebLogin')->controller(BannerControllerWeb::class)->group(function () {
    Route::get('/admin/banner-create', 'banner_create_view');
    Route::post('/admin/banner_create', 'banner_create');
    Route::get('/admin/delete_banner', 'delete_banner');
    Route::get('/admin/delete_banner/{id}', 'delete_banner');
    Route::post('/admin/edit_banner/{id}', 'edit_banner');
    Route::get('/admin/banners', 'banners');
    Route::get('/admin/banners/{id}', 'banners');
    Route::get('/admin/banner-edit/{id}','banner_edit');
});

Route::middleware('AdminWebLogin')->controller(SubcategoryControllerWeb::class)->group(function () {
    Route::get('/admin/sub-category-create', 'sub_category_create_view');
    Route::post('/admin/sub_category_create', 'sub_category_create');
    Route::get('/admin/delete_sub_category', 'delete_sub_category');
    Route::get('/admin/delete_sub_category/{id}', 'delete_sub_category');
    Route::get('/admin/sub-category-edit/{id}', 'edit_sub_category_view');
    Route::post('/admin/edit_sub_category/{id}', 'edit_sub_category');
    Route::get('/admin/sub-categories', 'sub_categories');

});

Route::middleware('AdminWebLogin')->controller(AdminController::class)->group(function () {
    Route::get('/v3/delete_user/{id}', 'delete_user');
});


Route::middleware('AdminWebLogin')->controller(ProductControllerWeb::class)->group(function () {
    Route::post('/admin/product_create', 'product_create');
    Route::get('/admin/delete_product', 'delete_product');
    Route::get('/admin/delete_product/{id}', 'delete_product');
    Route::post('/admin/edit_product/{id}', 'edit_product');
    Route::get('/admin/products', 'products');
    Route::get('/admin/products/{id}', 'products');
    Route::get('/admin/product-create','product_create_view');
    Route::get('/admin/product-edit/{id}', 'product_edit_view');
});

Route::middleware('AdminWebLogin')->controller(OrderControllerWeb::class)->group(function () {
    Route::get('/admin/carts', 'carts');
    Route::get('/admin/orders', 'orders');
    Route::post('/admin/order_update/{id}', 'order_update_status');
    Route::get('/admin/delete_cart/{id}','delete_add_to_cart');
    Route::get('/admin/order-detail/{id}','order_detail');
});


Route::middleware('AdminWebLogin')->controller(RestaurantControllerWeb::class)->group(function () {
    Route::get('/admin/restaurant-create', 'restaurant_create_view');
    Route::get('/admin/restaurants', 'restaurants');
    Route::get('/admin/restaurants/{id}', 'restaurants');
    Route::post('/admin/restaurant_create', 'restaurant_create');
    Route::post('/admin/edit_restaurant/{id}', 'edit_restaurant');
    Route::get('/admin/delete_restaurant/{id}', 'delete_restaurant');
    Route::get('/admin/restaurant-edit/{id}','restaurant_edit');
    Route::post('/admin/update_restaurant_status/{id}', 'update_restaurant_status');
});



Route::middleware('AdminWebLogin')->controller(AdminControllerWeb::class)->group(function () {
    Route::post('/admin/admin_create', 'admin_create');
    Route::get('/admin/delete_admin', 'delete_admin');
    Route::get('/admin/delete_admin/{id}', 'delete_admin');
    Route::post('/admin/edit_admin/{id}', 'edit_admin');
    Route::get('/admin/admin-edit/{id}', 'edit_admin_view');
    Route::get('/admin/admins', 'admins');
    Route::get('/admin/admins/{id}', 'admins');
    Route::get('/admin/admin-create', 'admin_create_view');
});

Route::middleware('AdminWebLogin')->controller(DriverControllerWeb::class)->group(function () {
    Route::post('/admin/driver_create', 'driver_create');
    Route::get('/admin/delete_driver', 'delete_driver');
    Route::get('/admin/delete_driver/{id}', 'delete_driver');
    Route::post('/admin/edit_driver/{id}', 'edit_driver');
    Route::get('/admin/driver-edit/{id}', 'edit_driver_view');
    Route::get('/admin/drivers', 'drivers');
    Route::get('/admin/drivers/{id}', 'drivers');
    Route::get('/admin/driver-create', 'driver_create_view');
    Route::post('/admin/update_driver_status/{id}', 'update_driver_status');
});


Route::middleware('AdminWebLogin')->controller(RestaurantOwnerControllerWeb::class)->group(function () {
    Route::post('/admin/owner_create', 'owner_create');
    Route::get('/admin/delete_owner', 'delete_owner');
    Route::get('/admin/delete_owner/{id}', 'delete_owner');
    Route::post('/admin/edit_owner/{id}', 'edit_owner');
    Route::get('/admin/owner-edit/{id}', 'edit_owner_view');
    Route::get('/admin/owners', 'owners');
    Route::get('/admin/owners/{id}', 'owners');
    Route::get('/admin/owner-create', 'owner_create_view');
    Route::post('/admin/update_owner_status/{id}', 'update_owner_status');
});


Route::middleware('AdminWebLogin')->controller(DeliveryControllerWeb::class)->group(function () {
    Route::post('/admin/delivery_fee_create', 'delivery_fee_create');
    Route::get('/admin/delete_delivery_fee', 'delete_delivery_fee');
    Route::get('/admin/delete_delivery_fee/{id}', 'delete_delivery_fee');
    Route::post('/admin/edit_delivery_fee/{id}', 'edit_delivery_fee');
    Route::get('/admin/delivery-fee-edit/{id}', 'delivery_fee_edit');
    Route::get('/admin/delivery_fees', 'delivery_fees');
    Route::get('/admin/delivery_fees/{id}', 'delivery_fees');
    Route::get('/admin/delivery-fee-create', 'delivery_fee_create_view');
    Route::post('/admin/update_delivery_fee_status/{id}', 'update_delivery_fee_status');
});




