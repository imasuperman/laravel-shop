<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes( [ 'verify'=>true ] );
Route::group( [ 'middleware'=>[ 'auth' , 'verified' ] ] , function()
{
    Route::resource( 'user_address' , 'UserAddressesController' );
    Route::post( 'products/{product}/favorite' , 'ProductsController@favorite' )->name( 'products.favorite' );
    Route::delete( 'products/{product}/favorite' , 'ProductsController@disfavorite' )->name( 'products.disfavorite' );
} );
Route::redirect( '/' , '/products' )->name( 'root' );
Route::get( 'products' , 'ProductsController@index' )->name( 'products.index' );
Route::get( 'products/{product}' , 'ProductsController@show' )->name( 'products.show' );

