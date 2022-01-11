<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Routing\RouteGroup;

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
Route::get('products', [ProductController::class, 'getAll']);
    Route::get('products/{id}', [ProductController::class, 'getOne']);
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);
Route::group(['middleware' => ['auth:sanctum']], function() {
    //PRODUCTS ========================================================================
    // Route::get('products', [ProductController::class, 'getAll']);
    // Route::get('products/{id}', [ProductController::class, 'getOne']);
    // Route::post('products', [ProductController::class, 'store']);
    // Route::put('products/{id}', [ProductController::class, 'update']);
    // Route::delete('products/{id}', [ProductController::class, 'destroy']);

























    //LOGOUT ========================================================================
    Route::post('/logout', [AuthController::class,'logout']);
});

//REGISTER ========================================================================
Route::post('/register', [AuthController::class,'register']);
//LOGIN ========================================================================
Route::post('/login', [AuthController::class,'login']);


Route::get('/', function(){
    return 'hello';
});


