<?php
/**
 * 辅助函数
 */
use Illuminate\Support\Str;

if (!function_exists('output_json'))
{
    /**
     * 输出json格式数据
     * @param $status
     * @param $code
     * @param array $data
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     * @author PeakXin<xinyflove@sina.com>
     */
    function output_json($status, $code, $data=[], $msg='')
    {
        if (empty($msg))
        {
            $msg = config('error.10000');
            !empty(config('error.'.$code)) && $msg = config('error.'.$code);
        }

        $arr = [
            'status' => $status,
            'errorCode' => $code,
            'msg' => $msg
        ];
        $status && $arr['data'] = $data;

        return response()->json($arr);
    }
}

if (! function_exists('success_json'))
{
    /**
     * 输出操作成功json格式数据
     * @param $data
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     * @author PeakXin<xinyflove@sina.com>
     */
    function success_json($data=[], $msg='')
    {
        return output_json(true, 200, $data, $msg);
    }
}

if (! function_exists('error_json'))
{
    /**
     * 输出操作失败son格式数据
     * @param $code
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     * @author PeakXin<xinyflove@sina.com>
     */
    function error_json($code, $msg='')
    {
        return output_json(false, $code, [], $msg);
    }
}

if (! function_exists('error_msg'))
{
    /**
     * 返回错误消息
     * @param $code
     * @return mixed
     * @author PeakXin<xinyflove@sina.com>
     */
    function error_msg($code)
    {
        $msg = config('error.10000');
        !empty(config('error.'.$code)) && $msg = config('error.'.$code);
        return $msg;
    }
}

if (! function_exists('generate_token'))
{
    /**
     * 生成token值
     * @param int $len 生成长度
     * @return string
     * @author PeakXin<xinyflove@sina.com>
     */
    function generate_token($len=60)
    {
        return Str::random($len);
    }
}