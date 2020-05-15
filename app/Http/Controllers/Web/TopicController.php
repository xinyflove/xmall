<?php

namespace App\Http\Controllers\Web;

use App\Models\Post;
use App\Models\PostTopic;
use App\Models\Topic;
use Illuminate\Http\Request;

/**
 * 专题控制器
 * Class TopicController
 * @package App\Http\Controllers\Web
 */
class TopicController extends Controller
{
    /**
     * 专题详情页
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Topic $topic)
    {
        // 带文章数的专题
        $topic = Topic::withCount('postTopics')->find($topic->id);
        // 专题的文字列表，按照创建时间倒序，前10个
        $posts = $topic->posts()->orderBy('created_at', 'desc')->with(['user'])->take(10)->get();
        // 属于我的文章，但是未投稿
        $myposts = Post::authorBy(\Auth::id())->topicNotBy($topic->id)->get();
        
        return view('topic/show', compact('topic', 'posts', 'myposts'));
    }

    /**
     * 专题投稿
     * @param Topic $topic
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function submit(Topic $topic)
    {
        $this->validate(request(), [
            'post_ids' => 'required|array'
        ]);

        // 确认这些post都是属于当前用户的
        $posts = Post::find(request(['post_ids']));
        foreach ($posts as $post) {
            if ($post->user_id != \Auth::id()) {
                return back()->withErrors(array('message' => '没有权限'));
            }
        }

        $post_ids = request('post_ids');
        $topic_id = $topic->id;

        foreach ($post_ids as $post_id)
        {
            PostTopic::firstOrCreate(compact('topic_id', 'post_id'));
        }

        return back();
    }
}
