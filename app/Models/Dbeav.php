<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// 基础Model
class Dbeav extends Model
{
    protected $guarded = [];// 不可以注入的字段数据
    //protected $fillable = ['title', 'content'];// 可以注入的字段数据
}
