<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Exception;
class ProductsController extends Controller
{
    /**
     * 商品列表
     */
    public function index( Request $request )
    {
        //创建一个查询构造器
        $builder=Product::query()->where( 'on_sale' , true );
        //判断是否有 search 参数提交
        if( $search=$request->input( 'search' , '' ) ){
            $like='%' . $search . '%';
            $builder->where( function( $query ) use ( $like )
            {
                $query->where( 'title' , 'like' , $like )->orWhere( 'description' , 'like' , $like )->orWhereHas( 'skus' , function( $query ) use ( $like )
                {
                    $query->where( 'title' , 'like' , $like )->orWhere( 'description' , 'like' , $like );
                } );
            } );
        }

        //检测时是否有排序筛选
        if( $order=$request->input( 'order' , '' ) ){
            //是否以_asc 或 _desc结尾
            if( preg_match( '/^(.+)_(asc|desc)$/' , $order , $m ) ){
                if( in_array( $m[ 1 ] , [ 'price' , 'sold_count' , 'rating' ] ) ){
                    $builder->orderBy( $m[ 1 ] , $m[ 2 ] );
                }
            }
        }
        $products=$builder->paginate( 16 );

        return view( 'products.index' , [
            'products'=>$products ,
            'filters' =>[
                'search'=>$search ,
                'order' =>$order
            ]
        ] );
    }

    public function show(Product $product,Request $request)
    {
        if(!$product->on_sale){
            throw new Exception('商品未上架');
        }
        //渲染模板
        return view( 'products.show' ,compact('product'));
    }
}
