<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    const CHECKED = 1;
    const UNCHECKED = 1;

    protected $guarded = [];// 不可以注入的字段数据
}
