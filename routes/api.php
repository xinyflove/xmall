<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'V1'], function () {
    // 不需要登录和权限验证
    Route::group(['prefix' => 'test'], function () {
        Route::any('/', 'TestController@index'); // init test
    });

    /*用户模块*/
    Route::group(['prefix' => 'user'], function () {
        Route::post('login', 'UserController@login')->name('api.v1.user.login'); // 用户登录
        Route::post('register', 'UserController@register'); // 用户注册
        Route::post('check_valid', 'UserController@checkValid'); // 检查用户名
        Route::post('forget_get_question', 'UserController@forgetGtQuestion'); // 获取用户密码提示问题
        Route::post('forget_check_answer', 'UserController@forgetCheckAnswer'); // 检查密码提示问题答案
        Route::post('forget_reset_password', 'UserController@forgetResetPassword'); // 重置密码
    });

    Route::group(['prefix' => 'product'], function () {
        Route::get('list', 'ProductController@index'); // 获取商品列表
    });


    /*需要登录*/
    Route::group(['middleware' => ['checkuser']], function () {
        /*用户模块*/
        Route::group(['prefix' => 'user'], function () {
            Route::post('login_info', 'UserController@loginInfo'); // 用户登录信息
            Route::post('get_info', 'UserController@getInfo'); // 获取用户信息
            Route::post('update_info', 'UserController@updateInfo'); // 更新个人信息
            Route::post('reset_password', 'UserController@resetPassword'); // 登录状态下更新密码
        });
        /*购物车模块*/
        Route::group(['prefix' => 'cart'], function () {
            Route::get('product_count', 'CartController@productCount'); // 购物车数量
        });
    });
});