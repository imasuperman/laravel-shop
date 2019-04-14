<?php

namespace App\Services;


use App\Models\CartItem;


class CartService
{
    //获取购物车数据
    public function get()
    {
        return auth ()->user ()->cartItem ()->with ( [ 'productSku.product' ] )->get ();
    }


    //添加购物车
    public function add( $skuId , $amount )
    {
        $user = auth ()->user ();
        //查询是否在数据表中已有该商品
        if( $cart = $user->cartItem ()->where ( 'product_sku_id' , $skuId )->first () ){
            // 如果存在则直接叠加商品数量
            $cart->update ( [
                'amount' => $cart->amount + $amount ,
            ] );
        }else{
            //创建一条新的购物车数据
            $cart = new CartItem( [ 'amount' => $amount ] );
            $cart->user ()->associate ( $user );
            $cart->productSku ()->associate ( $skuId );
            $cart->save ();
        }

        return $cart;
    }


    //删除购物车
    public function remove( $skuIds )
    {
        // 可以传单个 ID，也可以传 ID 数组
        if( !is_array ( $skuIds ) ){
            $skuIds = [ $skuIds ];
        }
        auth ()->user ()->cartItem ()->whereIn ( 'product_sku_id' , $skuIds )->delete ();
    }
}
