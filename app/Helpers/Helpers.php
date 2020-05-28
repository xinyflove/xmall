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

if (! function_exists('msectime'))
{
    /**
     * 毫秒数
     * @return string 返回当前的毫秒时间戳
     * @author PeakXin<xinyflove@sina.com>
     */
    function msectime()
    {
        list($tmp1, $tmp2) = explode(' ', microtime());
        return sprintf('%.0f', (floatval($tmp1) + floatval($tmp2)) * 1000);
    }
}

if (! function_exists('generate_sn'))
{
    /**
     * 生成编号
     * @param string $type 编号类型
     * @return string
     * @author PeakXin<xinyflove@sina.com>
     */
    function generate_sn($type='')
    {
        switch ($type)
        {
            case 1:// 订单编号
                $str = $type . substr(msectime().rand(100, 999), 1);
                break;
            case 2:// 支付单编号
                $str = $type . substr(msectime().rand(0, 9), 1);
                break;
            case 3:// 商品编号
                $str = 'G'.substr(msectime().rand(0, 5), 1);
                break;
            case 4:// 货品编号
                $str = 'P'.substr(msectime().rand(0, 5), 1);
                break;
            case 5:// 售后单编号
                $str = $type.substr(msectime().rand(0, 9), 1);
                break;
            case 6:// 退款单编号
                $str = $type.substr(msectime().rand(0, 9), 1);
                break;
            case 7:// 退货单编号
                $str = $type.substr(msectime().rand(0, 9), 1);
                break;
            case 8:// 发货单编号
                $str = $type.substr(msectime().rand(0, 9), 1);
                break;
            case 9:         //提货单号
                $chars = ['Q','W','E','R','T','Y','U','P','A','S','D','F','G','H','J','K','L','Z','X','C','V','B','N','M','2','3','4','5','6','7','8','9'];
                $charsLen = count($chars) - 1;
                shuffle($chars);
                $str = '';
                for($i = 0; $i < 6; $i++)
                {
                    $str .= $chars[mt_rand(0, $charsLen)];
                }
                break;
            default:
                $str = substr(msectime().rand(0, 9), 1);
        }

        return $str;
    }
}

if (! function_exists('get_rand_chars'))
{
    /**
     * 获取指定长度的随机字符串
     * @param $length
     * @return string
     * @author PeakXin<xinyflove@sina.com>
     */
    function get_rand_chars($length)
    {
        $str = '';
        $strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $max = strlen($strPol) - 1;

        for($i=0; $i<$length; $i++)
        {
            $str .= $strPol[rand(0, $max)];
        }

        return $str;
    }
}

