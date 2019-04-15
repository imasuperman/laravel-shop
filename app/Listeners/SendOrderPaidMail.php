<?php

namespace App\Listeners;


use App\Events\OrderPaid;
use App\Notifications\OrderPaidNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


class SendOrderPaidMail
{
    public function handle( OrderPaid $event )
    {
        $order = $event->getOrder ();
        //调用 notify 发送通知
        $order->user->notify ( new OrderPaidNotification( $order ) );
    }
}
