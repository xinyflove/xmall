<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('用户名称');
            $table->string('username')->unique()->comment('用户账号');
            $table->string('password', 100)->comment('登录密码');
            $table->string('email')->comment('用户邮箱');
            $table->string('token', 80)->unique()->nullable()->comment('token值');
            $table->tinyInteger('status')->default(0)->comment('状态 -1删除/0禁用/1正常');
            $table->timestamps();
        });
        DB::statement("alter table `admin_users` comment '管理员表'");

        $password = 'asdf1234';
        $password_hash = bcrypt($password);
        $token = Str::random(60);
        $created_at = date('Y-m-d H:i:s', time());
        DB::table('admin_users')->insert([
            [
                'name'=>'超级管理员',
                'username'=>'admin',
                'password'=>$password_hash,
                'email'=>'admin.xmall.com',
                'token'=>$token,
                'status'=>1,
                'created_at'=>$created_at,
                'updated_at'=>$created_at
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
