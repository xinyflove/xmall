<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notice;

/**
 * 通知管理控制器
 * Class NoticeController
 * @package App\Http\Controllers\Admin
 */
class NoticeController extends Controller
{
    /**
     * 通知列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $notices = Notice::paginate(10);
        return view('admin.notice.index', compact('notices'));
    }

    /**
     * 添加通知页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.notice.create');
    }

    /**
     * 添加通知行为
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $this->validate(request(), [
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $notice = Notice::create(request(['title', 'content']));
        dispatch(new \App\Jobs\SendMessage($notice));

        return redirect('admin/notices');
    }
}