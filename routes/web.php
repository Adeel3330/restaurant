<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserControllerWeb;
use App\Http\Controllers\OrderControllerWeb;
use App\Http\Controllers\BannerControllerWeb;
use App\Http\Controllers\ProductControllerWeb;
use App\Http\Controllers\CategoryControllerWeb;
use App\Http\Controllers\RestaurantControllerWeb;
use App\Http\Controllers\SubcategoryControllerWeb;

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
    Route::get('/admin/order_update/{id}', 'order_update_status');
    Route::get('/admin/delete_cart/{id}','delete_add_to_cart');
    Route::get('/admin/order-detail/{id}','order_detail');
});


Route::middleware('AdminWebLogin')->controller(RestaurantControllerWeb::class)->group(function () {
    Route::view('/admin/restaurant-create', '/admin/restaurant-create');
    Route::get('/admin/restaurants', 'restaurants');
    Route::get('/admin/restaurants/{id}', 'restaurants');
    Route::post('/admin/restaurant_create', 'restaurant_create');
    Route::post('/admin/edit_restaurant/{id}', 'edit_restaurant');
    Route::get('/admin/delete_restaurant/{id}', 'delete_restaurant');
    Route::get('/admin/restaurant-edit/{id}','restaurant_edit');
});
