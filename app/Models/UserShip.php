<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserShip extends Model
{
    const SHIP_NOR = 0;
    const SHIP_DEF = 1;
    
    protected $guarded = [];// 不可以注入的字段数据
    protected $hidden = ['created_at', 'updated_at'];
}
