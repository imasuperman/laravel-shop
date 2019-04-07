<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'products' , function( Blueprint $table ){
            $table->increments( 'id' )->comment( '主键 id' );
            $table->string( 'title' )->comment( '商品标题' );
            $table->text( 'description' )->comment( '商品描述' );
            $table->string( 'image' )->comment( '商品封面图' );
            $table->boolean( 'on_sale' )->default( true )->comment( '商品是否在售卖默认1' );
            $table->float( 'rating' )->default( 5 )->comment( '商品平均评分' );
            $table->unsignedInteger( 'sold_count' )->default( 0 )->comment( '销量' );
            $table->unsignedInteger( 'review_count' )->default( 0 )->comment( '评价数量' );
            $table->decimal( 'price' , 10 , 2 )->comment( 'sku最低价格' );
            $table->timestamps();
        } , '产品信息表' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'products' );
    }
}
