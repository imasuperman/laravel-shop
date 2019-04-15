<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Models\OrderItem;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductSoldCount
{

    public function handle(OrderPaid $event)
    {
        //从订单事件中去除订单
        $order = $event->getOrder ();
        //预加载商品数据
        $order->load('orderItems.product');
        //循环遍历订单的商品
        foreach($order->orderItems as $item){
            //获取每一条商品数据
            $product   = $item->product;
            //计算商品销量
            //在订单列表(order_items)中查询当前商品数据
            $soldCount = OrderItem::query()
                ->where('product_id',$product->id)
                //订单是已支付
                ->whereHas ('order',function ($query){
                    $query->whereNotNull('paid_at');
                })->sum ('amount');
            //更新商品销量
            $product->update([
                'sold_count' => $soldCount,
            ]);
        }
    }
}
