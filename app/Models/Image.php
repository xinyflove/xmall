<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = [];// 不可以注入的字段数据
    public $timestamps = false;
}
