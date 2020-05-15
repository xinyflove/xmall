<?php

namespace App\Models;

class Topic extends Dbeav
{
    // 属于这个专题的所有文章
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_topics', 'topic_id', 'post_id');
    }

    // 专题的文章数，用于withCount
    public function postTopics()
    {
        return $this->hasMany(PostTopic::class, 'topic_id');
    }
}
