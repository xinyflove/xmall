<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;

/**
 * 文章管理控制器
 * Class PostController
 * @package App\Http\Controllers\Admin
 */
class PostController extends Controller
{
    /**
     * 文章列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $posts = Post::withoutGlobalScope('avaiable')
            ->where('status', 0)->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.post.index', compact('posts'));
    }

    /**
     * 更新文章状态
     * @param Post $post
     * @return array
     */
    public function status(Post $post)
    {
        $this->validate(request(), [
            'status' => 'required|in:-1,1',
        ]);

        $post->status = request('status');
        $post->save();

        return [
            'error' => 0,
            'msg' => ''
        ];
    }
}