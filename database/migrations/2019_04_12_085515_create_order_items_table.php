<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'order_items' , function( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->unsignedInteger( 'order_id' )->comment( '关联订单主键' );
            $table->foreign( 'order_id' )->references( 'id' )->on( 'orders' )->onDelete( 'cascade' );
            $table->unsignedInteger( 'product_id' )->comment( '关联商品' );
            $table->foreign( 'product_id' )->references( 'id' )->on( 'products' )->onDelete( 'cascade' );
            $table->unsignedInteger( 'product_sku_id' )->comment( '关联商品 sku 表' );
            $table->foreign( 'product_sku_id' )->references( 'id' )->on( 'product_skus' )->onDelete( 'cascade' );
            $table->unsignedInteger( 'amount' )->comment( '购买数量' );
            $table->decimal( 'price' , 10 , 2 )->comment( '商品单价' );
            $table->unsignedInteger( 'rating' )->nullable()->comment( '用户打分' );
            $table->text( 'review' )->nullable()->comment( '用户评价' );
            $table->timestamps();
            $table->timestamp( 'reviewed_at' )->nullable()->comment( '评价时间' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'order_items' );
    }
}
