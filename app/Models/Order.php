<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_WAIT_BUYER_PAY = 'WAIT_BUYER_PAY';// 已下单等待付款
    const STATUS_WAIT_SELLER_SEND_PRODUCTS = 'WAIT_SELLER_SEND_PRODUCTS';// 已付款等待发货
    const STATUS_WAIT_BUYER_CONFIRM_GOODS = 'WAIT_BUYER_CONFIRM_GOODS';// 已发货等待确认收货
    const STATUS_ORDER_FINISHED = 'ORDER_FINISHED';// 已完成
    const STATUS_ORDER_CLOSED = 'ORDER_CLOSED';// 已关闭(退款关闭订单)
    const STATUS_ORDER_CLOSED_BY_SYSTEM = 'ORDER_CLOSED_BY_SYSTEM';// 已关闭(卖家或买家主动关闭)

    const PAY_ONLINE = 'online';
    const PAY_OFFLINE = 'offline';

    protected $guarded = [];// 不可以注入的字段数据
}
