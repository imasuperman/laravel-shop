<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add( AddCartRequest $request )
    {
        $user  =$request->user();
        $skuId =$request->input( 'sku_id' );
        $amount=$request->input( 'amount' );
        //查询是否在数据表中已有该商品
        if( $cart=$user->cartItem()->where( 'product_sku_id' , $skuId )->first() ){
            // 如果存在则直接叠加商品数量
            $cart->update( [
                'amount'=>$cart->amount + $amount ,
            ] );
        }else{
            //创建一条新的购物车数据
            $cart=new CartItem( [ 'amount'=>$amount ] );
            $cart->user()->associate( $user );
            $cart->productSku()->associate( $skuId );
            $cart->save();
        }

        return [];

    }
}