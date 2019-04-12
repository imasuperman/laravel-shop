<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //退款状态
    const REFUND_STATUS_PENDING    = 'pending';
    const REFUND_STATUS_APPLIED    = 'applied';
    const REFUND_STATUS_PROCESSING = 'processing';
    const REFUND_STATUS_SUCCESS    = 'success';
    const REFUND_STATUS_FAILED     = 'failed';
    //物流状态
    const SHIP_STATUS_PENDING   = 'pending';
    const SHIP_STATUS_DELIVERED = 'delivered';
    const SHIP_STATUS_RECEIVED  = 'received';
    public static $refundStatusMap = [
        self::REFUND_STATUS_PENDING    => '未退款' ,
        self::REFUND_STATUS_APPLIED    => '已申请退款' ,
        self::REFUND_STATUS_PROCESSING => '退款中' ,
        self::REFUND_STATUS_SUCCESS    => '退款成功' ,
        self::REFUND_STATUS_FAILED     => '退款失败' ,
    ];
    public static $shipStatusMap   = [
        self::SHIP_STATUS_PENDING   => '未发货' ,
        self::SHIP_STATUS_DELIVERED => '已发货' ,
        self::SHIP_STATUS_RECEIVED  => '已收货' ,
    ];
    protected     $fillable        = [
        'no' ,
        'address' ,
        'total_amount' ,
        'remark' ,
        'paid_at' ,
        'payment_method' ,
        'payment_no' ,
        'refund_status' ,
        'refund_no' ,
        'closed' ,
        'reviewed' ,
        'ship_status' ,
        'ship_data' ,
        'extra' ,
    ];
    protected     $casts           = [
        'closed'    => 'boolean' ,//订单是否关闭
        'reviewed'  => 'boolean' ,//订单是否已评价
        'address'   => 'json' ,//订单地址
        'ship_data' => 'json' ,//物流状态
        'extra'     => 'json' ,//其他数据
    ];
    protected     $dates           = [
        'paid_at' ,//支付时间
    ];

    public static function boot()
    {
        parent::boot ();
        static::creating ( function ( $model )
        {
            if( !$model->no ){
                $model->no = static::findAvailableNo ();
                // 如果订单号生成失败,则终止订单
                if( !$model->no ){
                    return FALSE;
                }
            }
        } );
    }

    //关联用户
    public function user()
    {
        return $this->belongsTo ( User::class );
    }

    //关联订单列表
    public function orderItems()
    {
        return $this->hasMany ( OrderItem::class );
    }

    // 生成订单号
    public static function findAvailableNo()
    {
        // 订单流水号前缀
        $prefix = date ( 'YmdHis' );
        for( $i = 0 ; $i < 10 ; $i++ ){
            // 随机生成 6 位的数字
            $no = $prefix . str_pad ( random_int ( 0 , 999999 ) , 6 , '0' , STR_PAD_LEFT );
            // 判断是否已经存在
            if( !static::query ()->where ( 'no' , $no )->exists () ){
                return $no;
            }
        }
        \Log::warning ( 'find order no failed' );

        return FALSE;
    }
}
