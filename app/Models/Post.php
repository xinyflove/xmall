<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

// 表 => posts
class Post extends Dbeav
{
    // 关联用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // 评论模型
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }
    
    // 和用户进行关联
    public function zan($user_id)
    {
        return $this->hasOne(Zan::class)->where('user_id', $user_id);
    }
    
    // 文章的所有赞
    public function zans()
    {
        return $this->hasMany(Zan::class);
    }

    // 属于某个作者的文章
    public function scopeAuthorBy(Builder $query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
    
    // 文章所属专题
    public function postTopics()
    {
        return $this->hasMany(PostTopic::class, 'post_id', 'id');
    }

    // 不属于某个专题的文章
    public function scopeTopicNotBy(Builder $query, $topic_id)
    {
        $query->doesntHave('postTopics', 'and', function ($q) use ($topic_id) {
            $q->where('topic_id', $topic_id);
        });
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
