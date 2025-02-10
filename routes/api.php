<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
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
Route::patch('products/{id}', [ProductController::class, 'update']);
Route::apiResource('orders', OrderController::class);
Route::apiResource('payments', PaymentController::class);
Route::get('/payments/{id}', [PaymentController::class, 'show']);
