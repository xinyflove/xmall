<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminPermission;
use Illuminate\Http\Request;

/**
 * 权限控制器
 * Class PermissionController
 * @package App\Http\Controllers\Admin
 */
class PermissionController extends Controller
{
    /**
     * 权限列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $permissions = AdminPermission::paginate(10);

        return view('admin.permission.index', compact('permissions'));
    }

    /**
     * 权限创建页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.permission.add');
    }

    /**
     * 权限创建行为
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:3',
            'description' => 'required',
        ]);

        AdminPermission::create(request(['name', 'description']));

        return redirect('admin/permissions');
    }
}
