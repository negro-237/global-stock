<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\{
    OrderController,
    CategoryController,
    ProductController,
    CustomerController
};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function ($route) {

    $route->post('init-password', [LoginController::class, 'initPassword']);
    $route->post('logout', [LoginController::class, 'logout']);
    $route->apiResource('categories', CategoryController::class);
    $route->post('products/{id}/supplies', [ProductController::class, 'supply']);
    $route->get('dashboard', [ProductController::class, 'dashboard']);
    $route->apiResource('products', ProductController::class);
    $route->apiResource('orders', OrderController::class);
    $route->apiResource('customers', CustomerController::class);
   /*  $route->group(['middleware' => ['role:admin']], function ($route) {
        $route->apiResource('categories', CategoryController::class);
    }); */
});

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [LoginController::class, 'register']);
