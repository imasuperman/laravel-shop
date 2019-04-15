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
    //收藏
    Route::post( 'products/{product}/favorite' , 'ProductsController@favorite' )->name( 'products.favorite' );
    //取消收藏
    Route::delete( 'products/{product}/favorite' , 'ProductsController@disfavorite' )->name( 'products.disfavorite' );
    //我的收藏
    Route::get('products/favorites','ProductsController@favorites')->name('products.favorites');
    //添加购物车
    Route::post('cart', 'CartController@add')->name('cart.add');
    //购物车列表
    Route::get('cart', 'CartController@index')->name('cart.index');
    //删除购物车
    Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');
    //提交订单
    Route::post('orders', 'OrdersController@store')->name('orders.store');
    //订单列表
    Route::get('orders', 'OrdersController@index')->name('orders.index');
    //订单详情页面
    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');
    //支付宝支付
    Route::get('payment/{order}/alipay', 'PaymentController@payByAlipay')->name('payment.alipay');
    //支付宝支付同步通知
    Route::get('payment/alipay/return', 'PaymentController@alipayReturnUrl')->name('payment.alipay.notify_url');
    //微信支付
    Route::get('payment/{order}/wechat', 'PaymentController@payByWechat')->name('payment.wechat');
} );
Route::redirect( '/' , '/products' )->name( 'root' );
Route::get( 'products' , 'ProductsController@index' )->name( 'products.index' );
Route::get( 'products/{product}' , 'ProductsController@show' )->name( 'products.show' );
//支付宝支付异步通知
Route::post('payment/alipay/notify', 'PaymentController@alipayNotifyUrl')->name('payment.alipay.return_url');
//微信支付同步通知
Route::post('payment/wechat/notify', 'PaymentController@wechatNotifyUrl')->name('payment.wechat.notify');


