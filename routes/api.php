<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    "product" => "ProductController",
    "order" => "OrderController",
]);
Route::post("order/{id}/calculate", "OrderController@calculateCost");
Route::post("order/{id}/place", "OrderController@place");
//Route::resource("order", "OrderController");
//Route::post("product", "ProductController@store");
