<?php

namespace App\Jobs;


use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class CloseOrder implements ShouldQueue
{
    use Dispatchable , InteractsWithQueue , Queueable , SerializesModels;

    protected $order;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( Order $order , $delay )
    {
        $this->order = $order;
        // 设置延迟的时间，delay() 方法的参数代表多少秒之后执行
        $this->delay ( $delay );
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //如果订单已支付,返回
        if( $this->order->paid_at ){
            return;
        }

        //执行事务
        \DB::transaction ( function ()
        {
            // 将订单的 closed 字段标记为 true，即关闭订单
            $this->order->update ( [ 'closed' => TRUE ] );
            foreach( $this->order->orderItems as $item ){
                $item->productSku->incrementStock ( $item->amount );
            }
        } );
    }
}
