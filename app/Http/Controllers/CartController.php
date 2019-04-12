<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index( Request $request )
    {
        $cartItems=$request->user()->cartItem()->with( [ 'user' , 'productSku' ] )->get();
        $addresses=$request->user()->userAddress()->orderBy( 'last_used_at' , 'desc' )->get();

        return view( 'cart.index' , compact( 'cartItems' , 'addresses' ) );
    }

    /**
     * 添加购物车
     *
     * @param AddCartRequest $request
     *
     * @return array
     */
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

    //购物车删除
    public function remove( ProductSku $sku , Request $request )
    {
        $request->user()->cartItem()->where( 'product_sku_id' , $sku->id )->delete();

        return [];
    }
}
