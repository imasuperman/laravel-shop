<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable
        =[
            'title' ,
            'description' ,
            'image' ,
            'on_sale' ,
            'rating' ,
            'sold_count' ,
            'review_count' ,
            'price'
        ];

    protected $casts
        =[
            'on_sale'=>'boolean'
        ];

    public function skus()
    {
        return $this->hasMany( ProductSku::class );
    }

    public function getImageAttribute( $key )
    {

        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if( Str::startsWith( $key , [ 'http://' , 'https://' ] ) ){
            return $key;
        }

        return url('upload/' . $key);
    }
}
