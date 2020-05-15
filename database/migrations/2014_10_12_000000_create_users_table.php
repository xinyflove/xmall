<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('用户名称/昵称');
            $table->string('username')->unique()->comment('用户登陆账号');
            $table->string('password')->comment('登录密码');
            $table->string('email')->comment('用户邮箱');
            $table->string('token', 80)->unique()->nullable()->comment('token值');
            $table->string('avatar', 100)->default('')->comment('用户头像');
			$table->string('question', 50)->comment('密码提示问题');
			$table->string('answer', 50)->comment('密码提示问题答案');
            $table->tinyInteger('status')->default(0)->comment('状态 -1删除/0禁用/1正常');
            //$table->rememberToken();
            $table->timestamps();
        });
        DB::statement("alter table `users` comment '用户表'");

        $password = 'asdf1234';
        $password_hash = bcrypt($password);
        $token = Str::random(60);
        $created_at = date('Y-m-d H:i:s', time());
        DB::table('users')->insert([
            [
                'name'=>'测试用户',
                'username'=>'test',
                'password'=>$password_hash,
                'email'=>'test.xmall.com',
                'token'=>$token,
                'question'=>'',
                'answer'=>'',
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
        Schema::dropIfExists('users');
    }
}
