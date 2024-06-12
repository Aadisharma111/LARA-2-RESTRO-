<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
Route::get('/orders/food-items/{restaurantId}', [OrderController::class, 'fetchFoodItems'])->name('orders.food-items');
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Example of API route
Route::get('/example', function () {
    return response()->json(['message' => 'Hello, this is an example API route!']);
});