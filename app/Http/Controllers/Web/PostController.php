<?php

namespace App\Http\Controllers\Web;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Zan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 文章控制器
 * Class PostController
 * @package App\Http\Controllers\Web
 */
class PostController extends Controller
{
    /**
     * 文章列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // 记录日志
        /*$app = app();
        $log = $app->make('log');
        $log->info('post_index', ['data'=>'this is post index']);*/
        
        $posts = Post::orderBy('created_at', 'desc')->withCount(['comments', 'zans'])->paginate(6);
        $posts->load('user');// 预加载user关联模型

        return view('post/index', compact('posts'));
    }

    /**
     * 文章详情页面
     * @param Post $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Post $post)
    {
        $post->load('comments');// 预加载comment模型，使得模版中不需要再生成模型
        return view('post/show', compact('post'));
    }

    /**
     * 文章创建页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('post/create');
    }

    /**
     * 文章创建逻辑
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        // 验证
        $this->validate(request(), [
            'title' => 'required|string|max:100|min:5',
            'content' => 'required|string|min:10',
        ]);

        // 逻辑
        //dd(request()->all());// 数据打印
        /*
        $post = new Post();
        $post->title = request('title'); 
        $post->content = request('content');
        $post->save();
        */
        
        //$params = ['title'=>request('title'), 'content'=>request('content')];
        $user_id = Auth::id();
        $params = array_merge(request(['title', 'content']), compact('user_id'));
        $post = Post::create($params);
        
        // 渲染
        return redirect('posts');
    }

    /**
     * 文章编辑页面
     * @param Post $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Post $post)
    {
        return view('post/edit', compact('post'));
    }

    /**
     * 文章编辑逻辑
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Post $post)
    {
        // 验证
        $this->validate(request(), [
            'title' => 'required|string|max:100|min:5',
            'content' => 'required|string|min:10',
        ]);

        // 验证修改权限
        $this->authorize('update', $post);

        // 逻辑
        $post->title = request('title'); 
        $post->content = request('content');
        $post->save();
        
        // 渲染
        return redirect('posts/' . $post->id);
    }

    /**
     * 文章删除逻辑
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function delete(Post $post)
    {
        // 验证删除权限
        $this->authorize('delete', $post);

        $post->delete();

        return redirect('posts');
    }

    /**
     * 文章上传图片
     * @param Request $request
     * @return string
     */
    public function imageUpload(Request $request)
    {
        $path = $request->file('wangEditorH5File')->storePublicly(md5(Auth::id() . time()));
        return asset('storage/' . $path);
    }

    /**
     * 文章提交评论
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function comment(Post $post)
    {
        // 验证
        $this->validate(request(), [
            'content' => 'required|min:3',
        ]);

        // 逻辑
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->content = request('content');
        $post->comments()->save($comment);

        // 渲染
        return back();
    }

    /**
     * 文章点赞
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function zan(Post $post)
    {
        $params = [
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ];
        
        Zan::firstOrCreate($params);// 先查找是否有参数数据，如果有查找出来，没有创建
        
        return back();
    }

    /**
     * 文章取消赞
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function unzan(Post $post)
    {
        $post->zan(Auth::id())->delete();
        return back();
    }

}
