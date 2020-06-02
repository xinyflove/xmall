<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "adminapi" middleware group. Enjoy building your Admin API!
|
*/

Route::group(['prefix' => 'v1', 'namespace' => 'V1'], function () {
    // 不需要登录和权限验证
    Route::group(['prefix' => 'test'], function () {
        Route::any('/', 'TestController@index'); // init test
    });

    /*用户模块*/
    Route::group(['prefix' => 'admin'], function () {
        Route::post('user/login', 'AdminUserController@login'); // 管理员登录
    });

    /*需要登录*/
    Route::group(['middleware' => ['checkadmin']], function () {
        /*管理员模块*/
        Route::group(['prefix' => 'admin'], function () {
            Route::get('user/login_info', 'AdminUserController@loginInfo'); // 登录信息
        });
    });
});