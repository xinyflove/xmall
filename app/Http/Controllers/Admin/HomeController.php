<?php

namespace App\Http\Controllers\Admin;

/**
 * 首页控制器
 * Class HomeController
 * @package App\Http\Controllers\Admin
 */
class HomeController extends Controller
{
    /**
     * 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.home.index');
    }
}