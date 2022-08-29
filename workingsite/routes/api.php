<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
//use App\Post;

//use App\Item;
//use App\Http\Controllers\CartController;

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

// example
// Route::get('product/{id}/{filter}', 'ProductController@filter'); 

//Route::middleware('auth:api')->get('/user', function (Request $request) {
// non token, remove auth:
// Route::middleware('api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Route::middleware('api')->get('item/{id}/{sku}', 'ItemController@getSku');


/*Route::get('App\Http\Controllers\API\PlpController', function ($id) {
    return $post;
});*/

//Route::middleware('api')->post('/PlpController', '\App\Http\Controllers\API\PlpController@modal');
//Route::middleware('api')->post('/item/{id}', '\App\Http\Controllers\API\PlpController@modal');


// [!] NO NEED FOR API, USE WEB ROUTE; POST

/*Route::middleware('api')->post('/item/{id}', function ($id) {
    $item = new Item();
    $itemObj = $item->getItem($id);
    return $itemObj;
});
Route::middleware('api')->post('/item-filter/{id}', function ($id) {
    $item = new Item();
    $itemObj = $item->getItemFilter($id);
    return $itemObj;
});*/

/*Route::middleware('api')->post('/sample-add', function (Request $request) {
    $cart = new CartController();
    $cartObj = $cart->sampleAdd($request);
    return $cartObj;
});
Route::middleware('api')->post('/sample-delete', function (Request $request) {
    $cart = new CartController();
    $cartObj = $cart->sampleDelete($request);
    return $cartObj;
});
Route::middleware('api')->post('/sample-update', function (Request $request) {
    $cart = new CartController();
    $cartObj = $cart->sampleUpdate($request);
    return $cartObj;
});*/

//Route::middleware('api')->post('/sample', '\App\Http\Controllers\API\CartController@samplecart');

/*Route::post('\App\Http\Controllers\API\CartController', function (App\Post $post) {
    return $post;
});*/