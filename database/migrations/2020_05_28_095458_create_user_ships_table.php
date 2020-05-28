<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserShipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_ships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户ID');
            $table->string('name', 50)->comment('收货人');
            $table->string('mobile', 20)->comment('手机号码');
            $table->string('tel', 20)->comment('固定电话');
            $table->string('zip', 10)->comment('邮编');
            $table->string('province', 20)->comment('所在省');
            $table->string('city', 20)->comment('所在市');
            $table->string('district', 20)->comment('所在县/市/区');
            $table->string('address', 50)->comment('详细地址');
            $table->unsignedTinyInteger('def')->default(0)->comment('详细地址');
            $table->timestamps();
        });
        DB::statement("alter table `user_ships` comment '用户收货地址表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_ships');
    }
}
