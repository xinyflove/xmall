<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 100)->comment('商品标题');
            $table->string('subtitle')->comment('商品副标题');
            $table->decimal('price', 20, 2)->comment('商品价格');
            $table->integer('stock')->comment('商品库存');
            $table->integer('cat_id')->default(0)->comment('商品分类ID');
            $table->string('main_img')->comment('商品主图');
            $table->string('image_list')->comment('商品图列表');
            $table->text('pc_desc')->comment('PC端商品详情');
            $table->text('wap_desc')->comment('WAP端商品详情');
            $table->tinyInteger('status')->default(0)->comment('状态 -1删除/0库中/1上架');
            $table->timestamps();
        });
        DB::statement("alter table `products` comment '商品主表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
