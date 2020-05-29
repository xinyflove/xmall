<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    const CHECKED = 1;
    const UNCHECKED = 0;

    /**
     * 关联 products 表
     * @return $this
     */
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')
            ->select(['id', 'title', 'price', 'stock', 'main_img', 'status']);
    }

    protected $guarded = [];// 不可以注入的字段数据
}
