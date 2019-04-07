<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * 商品列表
     */
    public function index()
    {

        $products=Product::query()->where( 'on_sale' , true )->paginate(16);

        return view( 'products.index' , compact( 'products' ) );
    }
}
