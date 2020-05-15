<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    protected $rememberTokenName = '';
    protected $fillable = [
        'name', 'password'
    ];

    // 用户有哪些角色
    public function roles()
    {
        return $this->belongsToMany(AdminRole::class, 'admin_role_user', 'user_id', 'role_id')
            ->withPivot(['user_id', 'role_id']);
    }
    
    // 判断是否有某个角色或某些角色
    public function isInRoles($roles)
    {
        // intersect 获取两个集合的交集
        return !! $roles->intersect($this->roles)->count();
    }
    
    // 给用户分配角色
    public function assignRole($role)
    {
        return $this->roles()->save($role);
    }
    
    // 取消用户分配的角色
    public function deleteRole($role)
    {
        return $this->roles()->detach($role);
    }
    
    // 判断是否有权限
    public function hasPermission($permission)
    {
        // 拥有这个权限的角色 和 我当前这个角色 是否有交集
        return $this->isInRoles($permission->roles);
    }
}
