<?php

namespace App\Http\Controllers;


use App\Exceptions\InvalidRequestException;
use App\Http\Requests\OrderRequest;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\ProductSku;
use App\Models\UserAddress;
use App\Services\CartService;
use App\Services\OrderService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;


/**
 * 武斌 <wubin.mail@foxmail.com>
 * Class OrdersController
 *
 * @package App\Http\Controllers
 */
class OrdersController extends Controller
{
    //订单列表
    public function index( Request $request )
    {
        $orders = Order::query ()->with ( [ 'orderItems.product' , 'orderItems.productSku' ] )->where ( 'user_id' , $request->user ()->id )->orderBy ( 'created_at' , 'desc' )->paginate ();

        return view ( 'orders.index' , compact ( 'orders' ) );
    }


    //购物车添加订单
    public function store( OrderRequest $request , OrderService $orderService )
    {
        // 获取用户选择的地址数据
        $address = UserAddress::find ( $request->input ( 'address_id' ) );
        $order   = $orderService->store ( $address , $request->input ( 'remark' ) , $request->input ( 'items' ) );

        return $order;
    }


    public function show( Order $order , Request $request )
    {
        $this->authorize ( 'own' , $order );

        return view ( 'orders.show' , compact ( 'order' ) );
    }
}
