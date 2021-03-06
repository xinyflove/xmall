<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const STATUS_DELETE = -1;// 已删除
    const STATUS_IN_STOCK = 0;// 未上架
    const STATUS_ON_SALE = 0;// 已上架
    //protected $guarded = [];// 不可以注入的字段数据

    /**
     * 获取排序规则
     * @param string $order_by
     * @return array|string
     */
    public function getOrderBy($order_by='default')
    {
        $fields = ['created_at', 'price'];
        $sort = ['DESC', 'ASC'];
        if ($order_by != 'default')
        {
            $order_by = explode('_', $order_by);
            if (count($order_by) == 2)
            {
                $order_by[1] = strtoupper($order_by[1]);
                if (in_array($order_by[0], $fields) && in_array($order_by[1], $sort))
                {
                    return $order_by;
                }
            }

        }

        return [$fields[0], $sort[0]];
    }

    // 全局scope的方式
    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        // 实现软删除
        static::addGlobalScope('avaiable', function (Builder $build) {
            $build->whereIn('status', [0, 1]);
        });
    }
}
