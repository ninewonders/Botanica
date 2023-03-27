<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckUserRole;

// Public routes

Route::get('Cart/{id}', [CartController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('Product', [ProductController::class, 'index']);
Route::get('Category', [CategoryController::class, 'index']);
Route::get('getProductsByCategory/{id}', [CategoryController::class, 'getProductsByCategory']);

Route::get('Category/searchByName', [CategoryController::class, 'searchByName']);
Route::get('Product/searchByName', [ProductController::class, 'searchByName']);

// Protected routes
Route::group(['middleware'=>['auth:sanctum']],function () 
{
    //Admin and Client Routes
    Route::post('/logout', [AuthController::class, 'logout']);

        //Client Routes 
        Route::get('Cart', [CartController::class, 'index']);
        Route::post('Cart', [CartController::class, 'store']);
        Route::post('addToCart', [CartController::class, 'addToCart']);
        Route::post('minusQuantity', [CartController::class, 'minusQuantity']);
        Route::post('addQuantity', [CartController::class, 'addQuantity']);
        Route::delete('deleteCartItem', [CartController::class, 'deleteCartItem']);
        Route::get('calculateCartTotals', [CartController::class, 'calculateCartTotals']);

        Route::delete('Cart/delete', [CartController::class, 'destroy']);
   
        Route::get('Order', [OrderController::class, 'index']);
        Route::post('Order', [OrderController::class, 'store']);
        Route::delete('Order/delete', [OrderController::class, 'destroy']);

            //Admin Routes
            Route::middleware([CheckUserRole::class])->group(function () {
                
                Route::get('allOrders', [OrderController::class, 'allOrders']);
                Route::post('updateStatus', [OrderController::class, 'updateStatus']);

                Route::post('Category', [CategoryController::class, 'store']);
                Route::get('Category/{id}', [CategoryController::class, 'show']);
                Route::put('Category/{id}/show', [CategoryController::class, 'update']);
                Route::delete('Category/{id}/delete', [CategoryController::class, 'destroy']);
                Route::post('Product', [ProductController::class, 'store']);
                Route::get('Product/{id}', [ProductController::class, 'show']);
                Route::put('Product/{id}/show', [ProductController::class, 'update']);
                Route::delete('Product/{id}/delete', [ProductController::class, 'destroy']);
            });
});
   
// Route::get('OrderItem', [OrderItemController::class, 'index']);
// Route::get('OrderItem/{id}', [OrderItemController::class, 'show']);
// Route::post('OrderItem', [OrderItemController::class, 'store']);
// Route::put('OrderItem/{id}/show', [OrderItemController::class, 'update']);
// Route::delete('OrderItem/{id}/delete', [OrderItemController::class, 'destroy']);

Route::get('CartItem', [CartItemController::class, 'index']);
Route::post('CartItem', [CartItemController::class, 'store']);
Route::get('CartItem/{id}', [CartItemController::class, 'show']);
Route::put('CartItem/{id}/show', [CartItemController::class, 'update']);
Route::delete('CartItem/{id}/delete', [CartItemController::class, 'destroy']);
        

?>




