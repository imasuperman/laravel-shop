<?php

namespace App\Http\Controllers;


use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;
use App\Services\CartService;
use Illuminate\Http\Request;


class CartController extends Controller
{
    protected $cartService;


    public function __construct( CartService $cartService )
    {
        $this->cartService = $cartService;
    }


    //购物车首页
    public function index( Request $request )
    {
        $cartItems = $this->cartService->get ();
        $addresses = $request->user ()->userAddress ()->orderBy ( 'last_used_at' , 'desc' )->get ();

        return view ( 'cart.index' , compact ( 'cartItems' , 'addresses' ) );
    }


    //添加购物车
    public function add( AddCartRequest $request )
    {
        $this->cartService->add ( $request->input ( 'sku_id' ) , $request->input ( 'amount' ) );

        return [];

    }


    //购物车删除
    public function remove( ProductSku $sku , Request $request )
    {
        $this->cartService->remove ( $sku->id );

        return [];
    }
}
