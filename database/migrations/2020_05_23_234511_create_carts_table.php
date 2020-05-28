<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('product_id')->comment('商品ID');
            $table->integer('quantity')->comment('购买数量');
            $table->unsignedTinyInteger('checked')->default(0)->comment('是否选中 0未选;1已选');
            $table->decimal('cart_price', 20, 2)->comment('加入购物车时商品价格');
            $table->timestamps();
        });
        DB::statement("alter table `products` comment '购物车表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
