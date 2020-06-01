<?php

namespace App\Http\Middleware;

use App\Models\AdminUser;
use Closure;

/**
 * 验证管理员登录用户
 * @package App\Http\Middleware
 * @author PeakXin<xinyflove@sina.com>
 */
class CheckAdminUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->userInfo = [];// 登录用户数据
        $token = $request->header('token', '');
        $inputToken = $request->input('token', '');
        if ($inputToken) $token = $inputToken;
        if (empty($token))
        {
            return error_json(10100);
        }

        $userInfo = AdminUser::where('token', $token)->first();
        if (!$userInfo)
        {
            return error_json(10101);
        }

        $request->userInfo = $userInfo->toArray();
        
        return $next($request);
    }
}
