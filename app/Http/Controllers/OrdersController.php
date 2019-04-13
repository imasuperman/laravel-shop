<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Http\Requests\OrderRequest;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\ProductSku;
use App\Models\UserAddress;
use Carbon\Carbon;
use DB;

/**
 * 武斌 <wubin.mail@foxmail.com>
 * Class OrdersController
 *
 * @package App\Http\Controllers
 */
class OrdersController extends Controller
{
    //购物车添加订单
    public function store( OrderRequest $request )
    {
        $user  = $request->user ();
        $order = DB::transaction ( function () use ( $user , $request )
        {
            // 获取用户选择的地址数据
            $address = UserAddress::find ( $request->input ( 'address_id' ) );
            //更新地址时间
            $address->update ( [ 'last_used_at' => Carbon::now () ] );
            // 创建订单
            $order = new Order( [
                'address'      => [
                    'address'       => $address->full_address ,// 数据表没有该字段,我们在 UserAddress模型中使用 getAttribute 处理
                    'zip'           => $address->zip ,// 区号
                    'contact_name'  => $address->contact_name ,// 联系人
                    'contact_phone' => $address->contact_phone ,// 联系电话
                ] ,
                'remark'       => $request->input ( 'remark' ) ,// 备注
                'total_amount' => 0 ,// 购买数量
            ] );
            // 订单关联到当前用户
            $order->user ()->associate ( $user );
            // 保存数据
            $order->save ();

            $totalAmount = 0;
            $items       = $request->input ( 'items' );
            foreach( $items as $item ){
                $sku = ProductSku::find ( $item[ 'sku_id' ] );
                // make()方法可以新建一个关联关系对象,但不保存数据
                //等同于$item = new OrderItem(); $item->order()->associate($order);
                $orderItem = $order->orderItems ()->make ( [
                    'amount' => $item[ 'amount' ] ,
                    'price'  => $sku->price ,
                ] );
                $orderItem->product ()->associate ( $sku->product_id );//关联 product
                $orderItem->productSku ()->associate ( $sku );//关联 sku
                $orderItem->save ();

                $totalAmount += $sku->price * $item[ 'amount' ];

                // 更新商品库存
                if( $sku->decrementStock ( $item[ 'amount' ] ) <= 0 ){
                    throw new InvalidRequestException( '该商品库存不足' );
                }
            }
            // 更新订单总金额
            $order->update ( [ 'total_amount' => $totalAmount ] );

            // 将下单的商品从购物车中移除
            $skuIds = collect ( $items )->pluck ( 'sku_id' );
            $user->cartItem ()->whereIn ( 'product_sku_id' , $skuIds )->delete ();

            return $order;
        } );

        //添加订单之后,触发任务
        $this->dispatch(new CloseOrder($order, config('app.order_ttl')));
        return $order;
    }
}
