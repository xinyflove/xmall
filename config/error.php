<?php
/**
 * 错误提示配置文件
 */

return [
    /*成功提示*/
    '200' => '成功',

    /*系统级别错误*/
    '10000' => '错误',
    '10001' => '参数错误',
    '10002' => '系统错误',
    '10003' => '数据库更新错误',
    '10004' => '路由不存在',
    '10005' => '语言参数错误',
    '10006' => '不允许的文件类型',
    '10007' => '上传失败',
    '10008' => '邮件发送失败',

    /*访问级别*/
    '10100' => '请先登录',
    '10101' => 'token错误或已过期',
    '10102' => '登录用户名或密码错误',
    '10103' => '没有操作权限',

    /*用户管理*/
    '10200' => '登录账号已存在',
    '10201' => '用户不存在',
    '10202' => '添加用户失败',
    '10203' => '删除用户失败',
    '10204' => '编辑用户失败',
    '10205' => '登录账号长度为3-20位',
    '10206' => '两次输入的密码不一致',
    '10207' => '密码提示问题答案错误',
    '10208' => '重置密码失败',
    '10209' => '更新个人信息失败',
    '10210' => '原密码错误',
    '10211' => '更新密码失败',

    /*商品模块*/
    '10300' => '商品不存在',
    '10301' => '商品已下架',
    '10302' => '商品已售罄',
    '10303' => '商品库存不足',
    
    /*购物车模块*/
    '10400' => '添加购物车失败',
    '10401' => '选择购物车商品失败',
    '10402' => '取消选择购物车商品失败',
    '10403' => '全部选择购物车商品失败',
    '10404' => '全部取消选择购物车商品失败',
    '10405' => '更新购物车商品数量失败',
    '10406' => '删除购物车商品失败',
    
    /*用户收货地址模块*/
    '10500' => '添加收货地址失败',
    '10501' => '收货地址信息不存在',
    '10502' => '删除收货地址失败',
    
    /*订单模块*/
    '10600' => '请选择商品下单',
    '10601' => '商品库存不足',
];
