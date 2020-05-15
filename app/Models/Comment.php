<?php

namespace App\Models;

class Comment extends Dbeav
{
    // 评论所属文章
    public function post()
    {
        //return $this->belongsTo('App\Models\Post');
        return $this->belongsTo(Post::class);
    }

    // 评论所属用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
