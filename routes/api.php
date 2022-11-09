<?php

use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\UsersController;
use App\Http\Controllers\api\CartsController;
use App\Http\Controllers\api\CategoriesController;
use App\Http\Controllers\api\OrdersController;
use App\Http\Controllers\api\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::options('{any}', function () {
    return response('OK', 204)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

Route::group(['middleware' => [],], function () {
    Route::post('/users/login', [LoginController::class, 'login']);
    Route::post('/users/register', [LoginController::class, 'register']);
});

Route::group(['middleware' => ['auth:api']], function () {

    Route::post('/users/logout', [LoginController::class, 'logout']);

    Route::group(['middleware' => ['trader']], function () {
        Route::post('/products/create', [ProductsController::class, 'create']);
        Route::put('/products/{id}/update', [ProductsController::class, 'update']);
        Route::delete('/products/{id}/delete', [ProductsController::class, 'delete']);
        Route::post('/users/{id}/searchbyTrader',[ProductsController::class, 'searchbyTrader']);

        Route::post('/categories/create', [CategoriesController::class, 'create']);
        Route::put('/categories/{id}/update', [CategoriesController::class, 'update']);
        Route::delete('/categories/{id}/delete', [CategoriesController::class, 'delete']);
    });
    Route::group(['middleware' => ['user']], function () {

        Route::post('/products/records', [ProductsController::class, 'records']);
        Route::post('/categories/records', [CategoriesController::class, 'records']);
        Route::post('/products/{id}', [ProductsController::class, 'record']);
        Route::post('/users/{id}/searchbycategory',[ProductsController::class, 'searchbycategory']);
        Route::post('/categories/{id}', [CategoriesController::class, 'record']);

        Route::post('/orders/create', [OrdersController::class, 'create']);
        Route::post('/orders/records', [OrdersController::class, 'records']);
        Route::put('/orders/{id}/update', [OrdersController::class, 'update']);
        Route::delete('/orders/{id}/delete', [OrdersController::class, 'delete']);
        Route::post('/orders/{id}', [OrdersController::class, 'record']);

        Route::post('/carts/create', [CartsController::class, 'create']);
        Route::post('/carts/records', [CartsController::class, 'records']);
        Route::put('/carts/{id}/update', [CartsController::class, 'update']);
        Route::delete('/carts/{id}/delete', [CartsController::class, 'delete']);
        Route::post('/carts/{id}', [CartsController::class, 'record']);
    });
    Route::group(['middleware' => ['admin']], function () {

        Route::post('/users/records', [UsersController::class, 'records']);
        Route::post('/users/activeUsers', [UsersController::class, 'activeUsers']);
        Route::post('/users/create', [UsersController::class, 'create']);
        Route::put('/users/{id}/update', [UsersController::class, 'update']);
        Route::delete('/users/{id}/delete', [UsersController::class, 'delete']);
        Route::post('/users/{type}/search',[UsersController::class, 'search']);
        Route::post('/users/{id}', [UsersController::class, 'record']);
    });
});
