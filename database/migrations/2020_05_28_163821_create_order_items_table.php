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
        Schema::create('order_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('id')->comment('子订单号');
            $table->unsignedBigInteger('oid')->comment('主订单号');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('product_id')->comment('商品ID');
            $table->string('title', 100)->comment('商品标题');
            $table->string('main_img')->comment('商品主图');
            $table->decimal('price', 20, 2)->comment('商品价格');
            $table->integer('quantity')->comment('购买数量');
            $table->decimal('payment', 20, 2)->default('0.00')->comment('实付金额');
            $table->decimal('points_fee', 20, 2)->default('0.00')->comment('积分抵扣的金额');
            $table->decimal('total_fee', 20, 2)->default('0.00')->comment('应付金额');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
