<?php

namespace App\Models;

use App\Exceptions\InternalException;
use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    protected $fillable = [ 'title' , 'description' , 'price' , 'stock' ];

    // 关联商品
    public function product()
    {
        return $this->belongsTo ( Product::class );
    }

    // 库存加
    public function incrementStock( $amount )
    {
        if( $amount < 0 ){
            throw  new InternalException( '加库存不可少于0' );
        }
        $this->increment ( $amount );
    }

    // 库存减
    public function decrementStock( $amount )
    {
        if( $amount < 0 ){
            throw  new InternalException( '减库存不可小于0' );
        }

        return $this->newQuery ()->where ( 'id' , $this->id )->where ( 'stock' , '>=' , $amount )->decrement ( 'stock' , $amount );
    }
}
