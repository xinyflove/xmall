<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

/**
 * 登录控制器
 * Class LoginController
 * @package App\Http\Controllers\Admin
 */
class LoginController extends Controller
{
    /**
     * 登录展示页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.login.index');
    }

    /**
     * 登录行为
     * @return Redirect
     */
    public function login()
    {
        // 验证
        $this->validate(request(), [
            'name' => 'required|min:2',
            'password' => 'required|min:5|max:10'
        ]);

        // 逻辑
        $user = request(['name', 'password']);
        if (Auth::guard('admin')->attempt($user))
        {
            return redirect('admin/home');
        }

        // 渲染
        return Redirect::back()->withErrors('用户名密码不匹配');
    }

    /**
     * 登出行为
     * @return Redirect
     */
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }
}