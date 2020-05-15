<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 通知消息控制器
 * Class NoticeController
 * @package App\Http\Controllers\Web
 */
class NoticeController extends Controller
{
    /**
     * 消息页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // 获取我收到的消息
        $user = Auth::user();
        $notices = $user->notices;

        return view('notice.index', compact('notices'));
    }
}
