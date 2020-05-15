<?php

namespace App\Http\Middleware;

use Closure;

/**
 * 允许跨域请求中间件
 * @package App\Http\Middleware
 * @author PeakXin<xinyflove@sina.com>
 */
class EnableCrossRequest
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
        header('Access-Control-Allow-Origin: *');// *代表允许任何网址请求
        header("Access-Control-Allow-Credentials: true");// 设置是否允许发送 cookies
        header("Access-Control-Allow-Methods: *");// 允许请求的类型
        header("Access-Control-Allow-Headers: Content-Type,Access-Token");// 设置允许自定义请求头的字段
        header("Access-Control-Expose-Headers: *");

        return $next($request);
    }
}
