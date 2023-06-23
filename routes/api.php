<?php

use App\Models\Driver;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Homecontroller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DriverControllerWeb;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\RestaurantOwnerController;
use App\Http\Controllers\RestaurantTimingController;

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
Route::controller(UserController::class)->group(function () {
    Route::post('/login', 'Login');
    Route::post('/register', 'register');
    // Route::post('update_password/{id}', 'update_password');
    Route::get('/users', 'getusers');
    Route::get('/users/{id}', 'getusers');
    Route::post('/forgot_password', 'forgot_password');
    Route::post('/otp_verified', 'otp_verified');
    Route::post('/change_password', 'change_password');
    Route::get('/cookie', 'cookies_get');
    Route::get('/get_otp_email', 'get_otp_email');
    Route::post('/v3/admin_login', 'AdminLogin');
});


Route::middleware('LoginApi')->controller(UserController::class)->group(function () {
    Route::post('/edit_user', 'edit_user');
});


Route::middleware('AdminLoginApi')->controller(UserController::class)->group(function () {
    Route::get('/v3/users', 'getusers');
    Route::get('/v3/users/{id}', 'getusers');
});

Route::middleware('LoginApi')->controller(Homecontroller::class)->group(function () {
    Route::post('/location_insert', 'location_insert');
    Route::post('/get_session', 'get_session');
   
});

Route::middleware('LoginApi')->controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'categories');
    Route::get('/categories/{id}', 'categories');
});
Route::middleware('AdminLoginApi')->controller(CategoryController::class)->group(function () {
    Route::post('/v3/category_create', 'category_create');
    Route::get('/v3/delete_category', 'delete_category');
    Route::get('/v3/delete_category/{id}', 'delete_category');
    Route::post('/v3/edit_category/{id}', 'edit_category');
    Route::get('/v3/categories', 'categories');
    Route::get('/v3/categories/{id}', 'categories');
 
});

Route::middleware('LoginApi')->controller(BannerController::class)->group(function () {
    Route::get('/banners', 'banners');
    Route::get('/banners/{id}', 'banners');
});
Route::middleware('AdminLoginApi')->controller(BannerController::class)->group(function () {
    Route::post('/v3/banner_create', 'banner_create');
    Route::get('/v3/delete_banner', 'delete_banner');
    Route::get('/v3/delete_banner/{id}', 'delete_banner');
    Route::post('/v3/edit_banner/{id}', 'edit_banner');
    Route::get('/v3/banners', 'banners');
    Route::get('/v3/banners/{id}', 'banners');
});



Route::middleware('LoginApi')->controller(SubcategoryController::class)->group(function () {
    Route::get('/sub_categories', 'sub_categories');
    Route::get('/sub_categories/{id}', 'sub_categories');
});
Route::middleware('AdminLoginApi')->controller(SubcategoryController::class)->group(function () {
    Route::post('/v3/sub_category_create', 'sub_category_create');
    Route::get('/v3/delete_sub_category', 'delete_sub_category');
    Route::get('/v3/delete_sub_category/{id}', 'delete_sub_category');
    Route::post('/v3/edit_sub_category/{id}', 'edit_sub_category');
    Route::get('/v3/sub_categories', 'sub_categories');
    Route::get('/v3/sub_categories/{id}', 'sub_categories');
});

Route::middleware('AdminLoginApi')->controller(AdminController::class)->group(function () {
    Route::get('/v3/delete_user/{id}', 'delete_user');
});



Route::middleware('LoginApi')->controller(ProductController::class)->group(function () {
    Route::get('/products', 'products');
    Route::get('/products/{id}', 'products');
    Route::post('/search_products', 'search_products');
    
});


Route::middleware('AdminLoginApi')->controller(ProductController::class)->group(function () {
    Route::post('/v3/product_create', 'product_create');
    Route::get('/v3/delete_product', 'delete_product');
    Route::get('/v3/delete_product/{id}', 'delete_product');
    Route::post('/v3/edit_product/{id}', 'edit_product');
    Route::get('/v3/products', 'products');
    Route::get('/v3/products/{id}', 'products');
});

Route::middleware('LoginApi')->controller(OrderController::class)->group(function () {
    Route::post('/add_to_cart', 'add_to_cart');
    Route::post('/order_create', 'order');
    Route::get('/delete_cart/{id}', 'delete_add_to_cart');
    Route::get('/order', 'order_get');
    Route::get('/order/{id}', 'order_get');

    Route::get('/cart', 'cart');
    Route::get('/cart/{id}', 'cart');
});

Route::middleware('AdminLoginApi')->controller(OrderController::class)->group(function () {

    Route::get('/v3/carts', 'carts');
    Route::get('/v3/carts/{id}', 'carts');
    Route::get('/v3/orders', 'orders');
    Route::get('/v3/orders/{id}', 'orders');
    Route::get('/v3/order_update/{id}', 'order_update_status');
});


Route::middleware('AdminLoginApi')->controller(RestaurantController::class)->group(function () {
    // Route::get('/get_distance', 'get_distance');
    Route::get('/v3/restaurants', 'restaurants');
    Route::get('/v3/restaurants/{id}', 'restaurants');
    Route::post('/v3/restaurant_create', 'restaurant_create');
    Route::post('/v3/edit_restaurant/{id}', 'edit_restaurant');
    Route::post('/v3/delete_restaurant/{id}', 'delete_restaurant');
});



Route::middleware('LoginApi')->controller(RestaurantController::class)->group(function () {
    Route::get('/get_distance', 'get_distance');
    Route::get('/restaurants', 'restaurants');
    Route::get('/restaurants/{id}', 'restaurants');
});


Route::controller(DriverController::class)->group(function () {
    Route::post('/register_driver', 'register_driver');
    Route::post('/login_driver', 'login_driver');
    Route::get('/restaurants_driver', 'restaurants_driver');
    Route::post('/driver_forgot_password', 'forgot_password');
    Route::post('/driver_otp_verified', 'otp_verified');
    Route::post('/driver_change_password', 'change_password');
    Route::get('/driver_cookie', 'cookies_get');
    Route::get('/driver_get_otp_email', 'get_otp_email');
   

});

Route::middleware('DriverLogin')->controller(DriverController::class)->group(function () {
    Route::get('/driver/orders', 'orders');
    Route::get('/driver/orders/{id}', 'orders');
    Route::post('/driver/order_update_status/{id}', 'order_update_status');
    Route::post('/driver/insert_location', 'insert_location');
    
});




Route::controller(RestaurantOwnerController::class)->group(function () {
    Route::post('/register_restaurant', 'restaurant_create');
    Route::post('/login_restaurant', 'login_restaurant');
    Route::post('/restaurant_forgot_password', 'forgot_password');
    Route::post('/restaurant_otp_verified', 'otp_verified');
    Route::post('/restaurant_change_password', 'change_password');
    Route::get('/restaurant_cookie', 'cookies_get');
    Route::get('/restaurant_get_otp_email', 'get_otp_email');
    Route::get('/restaurants', 'restaurants');
    
});


Route::controller(RestaurantController::class)->group(function () {
    Route::get('/restaurant_week_timings', 'restaurant_week_timings');
});
    

Route::middleware('RestaurantLogin')->controller(RestaurantController::class)->group(function () {
    Route::get('/restaurant/drivers','get_drivers');
    Route::get('/restaurant/orders', 'orders');
    Route::get('/restaurant/orders/{id}', 'orders');
    Route::post('/restaurant/order_update_status/{id}', 'order_update_status');
    Route::post('/restaurant/assign_driver', 'assign_driver');

});




