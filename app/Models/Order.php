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
    
    const STATUS_DESC = [
        self::STATUS_WAIT_BUYER_PAY => '待付款',
        self::STATUS_WAIT_SELLER_SEND_PRODUCTS => '待发货',
        self::STATUS_WAIT_BUYER_CONFIRM_GOODS => '待收货',
        self::STATUS_ORDER_FINISHED => '已完成',
        self::STATUS_ORDER_CLOSED => '已关闭',
        self::STATUS_ORDER_CLOSED_BY_SYSTEM => '已关闭',
    ];

    const CANCEL_STATUS_NO = 'NO_APPLY_CANCEL';// 未取消订单
    const CANCEL_STATUS_SUCCESS = 'SUCCESS';// 取消订单成功

    const PAY_ONLINE = 'online';
    const PAY_OFFLINE = 'offline';
    
    const PAY_TYPE_DESC = [
        self::PAY_ONLINE => '在线支付',
        self::PAY_OFFLINE => '线下支付',
    ];

    protected $guarded = [];// 不可以注入的字段数据

    /**
     * 关联 order_items 表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_item()
    {
        return $this->hasMany(OrderItem::class, 'oid', 'id');
    }

    /**
     * 通过订单状态判断订单是否可以取消
     * @param $status
     * @return bool
     */
    public static function getIsCancelable($status)
    {
        $arr = [
            self::STATUS_WAIT_BUYER_PAY,
            self::STATUS_WAIT_SELLER_SEND_PRODUCTS,
        ];
        
        if (in_array($status, $arr))
        {
            return true;
        }
        
        return false;
    }
}
