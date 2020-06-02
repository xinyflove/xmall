<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('上传人ID');
            $table->string('name')->commnet('文件名');
            $table->string('path', 200)->comment('文件路径');
            $table->string('url')->comment('绝对地址');
            $table->integer('width')->comment('宽度');
            $table->integer('height')->comment('高度');
            $table->integer('size')->comment('文件大小');
            $table->unsignedInteger('upload_time')->comment('上传时间');
            $table->index('user_id');
        });
        DB::statement("alter table `images` comment '图片管理表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
