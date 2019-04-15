<?php

namespace App\Http\Controllers;


use App\Events\OrderPaid;
use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;


class PaymentController extends Controller
{
    //支付宝支付
    public function payByAlipay( Order $order , Request $request )
    {
        $this->authorize ( 'own' , $order );
        if( $order->paid_at || $order->closed ){
            throw new InvalidRequestException( '订单状态不正确' );
        }

        // 调用支付宝的网页支付
        return app ( 'alipay' )->web ( [
            'out_trade_no' => $order->no , // 订单编号，需保证在商户端不重复
            'total_amount' => $order->total_amount , // 订单金额，单位元，支持小数点后两位
            'subject'      => '支付 Laravel Shop 的订单：' . $order->no , // 订单标题
        ] );
    }


    //微信支付
    public function payByWechat( Order $order , Request $request )
    {
        $this->authorize ( 'own' , $order );
        if( $order->paid_at || $order->closed ){
            throw new InvalidRequestException( '订单状态不正确' );
        }

        // 微信扫码支付
        $wechatOrder = app ( 'wechat_pay' )->scan ( [
            'out_trade_no' => $order->no ,  // 商户订单流水号，与支付宝 out_trade_no 一样
            'total_fee'    => $order->total_amount * 100 , // 与支付宝不同，微信支付的金额单位是分。
            'body'         => '支付 Laravel Shop 的订单：' . $order->no , // 订单描述
        ] );
        // 把要转换的字符串作为 QrCode 的构造函数参数
        $qrCode = new QrCode( $wechatOrder->code_url );

        return response ( $qrCode->writeString () , 200 , [ 'Content-Type' => $qrCode->getContentType () ] );
    }


    //支付宝同步通知
    public function alipayReturnUrl()
    {
        try{
            app ( 'alipay' )->verify ();
        }catch( \Exception $e ){
            return view ( 'pages.error' , [ 'msg' => '数据不正确' ] );
        }

        return view ( 'pages.success' , [ 'msg' => '付款成功' ] );
    }


    //支付宝异步通知
    public function alipayNotifyUrl()
    {
        $data = app ( 'alipay' )->verify ();
        if( !in_array ( $data->trade_status , [ 'TRADE_SUCCESS' , 'TRADE_FINISHED' ] ) ){
            return app ( 'alipay' )->success ();
        }
        //根据流水号获取订单数据
        $order = Order::where ( 'no' , $data->out_trade_no )->first ();
        if( !$order )
            return 'fail';
        // 如果这笔订单的状态已经是已支付
        if( $order->paid_at ){
            // 返回数据给支付宝
            return app ( 'alipay' )->success ();
        }
        $order->update ( [
            'paid_at'        => Carbon::now () , // 支付时间
            'payment_method' => 'alipay' , // 支付方式
            'payment_no'     => $data->trade_no , // 支付宝订单号
        ] );

        return app ( 'alipay' )->success ();
    }


    //微信支付异步通知
    public function wechatNotifyUrl()
    {
        // 校验回调参数是否正确
        $data = app ( 'wechat_pay' )->verify ();
        // 找到对应的订单
        $order = Order::where ( 'no' , $data->out_trade_no )->first ();
        // 订单不存在则告知微信支付
        if( !$order ){
            return 'fail';
        }
        // 订单已支付
        if( $order->paid_at ){
            // 告知微信支付此订单已处理
            return app ( 'wechat_pay' )->success ();
        }

        // 将订单标记为已支付
        $order->update ( [
            'paid_at'        => Carbon::now () ,
            'payment_method' => 'wechat' ,
            'payment_no'     => $data->transaction_id ,
        ] );
        //$this->afterPaid($order);
        //分发事件
        event ( new  OrderPaid( $order ) );

        return app ( 'wechat_pay' )->success ();
    }
}
