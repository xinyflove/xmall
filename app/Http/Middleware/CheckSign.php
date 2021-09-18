<?php

namespace App\Http\Middleware;

use Closure;

/**
 * 验证签名
 * @package App\Http\Middleware
 * @author PeakXin<xinyflove@sina.com>
 */
class CheckSign
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
        $data = $request->all();
        if (!verify_sign($data, $code))
        {
            return error_json($code);
        }

        return $next($request);
    }
}
