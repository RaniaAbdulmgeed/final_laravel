<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CustomProductController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\WishlistController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::ApiResource('categories' , CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::get('products/category/{categoryId}', [ProductController::class, 'getByCategory']);
Route::put('/payments/{id}', [PaymentController::class, 'update']);
Route::apiResource('orders', OrderController::class);
Route::apiResource('payments', PaymentController::class);
Route::get('/payments/{id}', [PaymentController::class, 'show']);
Route::put('/orders/{id}', [OrderController::class, 'update']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::put('/products/{id}', [ProductController::class, 'update']);

Route::get('products/category/{categoryId}', [ProductController::class, 'getByCategory']);

/***********************Routes of Login and register and logout user***** */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

/**************************** */

Route::post('/contact', [ContactController::class, 'store']);

/******************************/
Route::post('/reviews', [ReviewController::class, 'store']);
Route::get('/reviews', [ReviewController::class, 'index']);


/**************************************** */
Route::post('/custom-products', [CustomProductController::class, 'store']);
/******************************CHECKOUT************ */
Route::post('/checkout', [PaymentController::class, 'checkout']);

/***************WISHLIST ***************** */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy']);
});
/******************************** */
Route::options('/{any}', function () {
    return response()->json([], 204); // ✅ Responds OK for OPTIONS requests
})->where('any', '.*');
