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
        Route::get('detail', 'ProductController@detail'); // 获取商品详细信息
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
            Route::get('list', 'CartController@index'); // 购物车
            Route::get('add', 'CartController@add'); // 添加购物车
            Route::get('select', 'CartController@select'); // 选择购物车商品
            Route::get('un_select', 'CartController@unSelect'); // 取消选择购物车商品
            Route::get('select_all', 'CartController@selectAll'); // 选中全部商品
            Route::get('un_select_all', 'CartController@unSelectAll'); // 取消选中全部商品
            Route::get('update', 'CartController@update'); // 更新购物车商品数量
            Route::get('delete', 'CartController@delete'); // 删除指定商品
            Route::get('product_count', 'CartController@productCount'); // 购物车数量
        });
        /*订单模块*/
        Route::group(['prefix' => 'order'], function () {
            Route::get('get_cart_product', 'OrderController@getCartProduct'); // 获取产品列表信息
            Route::post('create', 'OrderController@create'); // 提交订单
            Route::get('pay', 'OrderController@pay'); // 获取支付信息
            Route::get('query_pay_status', 'OrderController@queryPayStatus'); // 获取订单状态
            Route::get('detail', 'OrderController@detail'); // 获取订单详情
            Route::get('cancel', 'OrderController@cancel'); // 取消订单
            Route::get('list', 'OrderController@index'); // 订单列表
        });
        /*用户收货地址模块*/
        Route::group(['prefix' => 'ship'], function () {
            Route::get('list', 'UserShipController@index'); // 获取地址列表信息
            Route::post('add', 'UserShipController@add'); // 新建收件人收货信息
            Route::get('edit', 'UserShipController@edit'); // 获取要编辑的收货人收货信息
            Route::post('update', 'UserShipController@update'); // 更新收件人收货信息
            Route::get('delete', 'UserShipController@delete'); // 删除收件人收货信息
        });
    });
});