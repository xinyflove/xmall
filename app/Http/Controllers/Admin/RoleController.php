<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminPermission;
use App\Models\AdminRole;
use Illuminate\Http\Request;

/**
 * 角色管理控制器
 * Class RoleController
 * @package App\Http\Controllers\Admin
 */
class RoleController extends Controller
{
    /**
     * 角色列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $roles = AdminRole::paginate(10);
        return view('admin.role.index', compact('roles'));
    }

    /**
     * 角色创建页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.role.add');
    }

    /**
     * 角色创建行为
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:3',
            'description' => 'required',
        ]);
        
        AdminRole::create(request(['name', 'description']));
        
        return redirect('admin/roles');
    }

    /**
     * 角色权限列表
     * @param AdminRole $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function permission(AdminRole $role)
    {
        // 获取所有权限
        $permissions = AdminPermission::all();
        
        // 获取当前角色的权限
        $myPermissions = $role->permissions;
        
        return view('admin.role.permission', compact('permissions', 'myPermissions', 'role'));
    }

    /**
     * 保存角色权限
     * @param AdminRole $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePermission(AdminRole $role)
    {
        $this->validate(request(), [
            'permissions' => 'required|array'
        ]);

        $permissions = AdminPermission::findMany(request('permissions'));
        $myPermissions = $role->permissions;

        // 要增加的
        $addPermissions = $permissions->diff($myPermissions);
        foreach ($addPermissions as $permission)
        {
            $role->grantPermission($permission);
        }

        // 要删除的
        $deletePermissions = $myPermissions->diff($permissions);
        foreach ($deletePermissions as $permission)
        {
            $role->deletePermission($permission);
        }

        return back();
    }
}
