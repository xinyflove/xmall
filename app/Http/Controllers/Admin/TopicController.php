<?php

namespace App\Http\Controllers\Admin;

use App\Models\Topic;

/**
 * 专题管理控制器
 * Class TopicController
 * @package App\Http\Controllers\Admin
 */
class TopicController extends Controller
{
    /**
     * 专题列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $topics = Topic::all();
        
        return view('admin.topic.index', compact('topics'));
    }

    /**
     * 专题创建页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.topic.create');
    }

    /**
     * 专题创建行为
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|string'
        ]);

        Topic::create(['name'=>request('name')]);

        return redirect('/admin/topics');
    }

    /**
     * 专题删除
     * @param Topic $topic
     * @return array
     * @throws \Exception
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();

        return [
            'error' => 0,
            'msg' => ''
        ];
    }
}