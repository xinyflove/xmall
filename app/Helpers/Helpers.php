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

if (! function_exists('get_sign'))
{
    /**
     * 获取签名
     * @param $data
     * @return string
     */
    function get_sign($data)
    {
        $secret = config('secure.sign_secret');
        // 对数组的值按key排序
        ksort($data);
        // 生成url的形式
        $params = http_build_query($data);
        // 生成sign
        return md5($params . $secret);
    }
}

if (! function_exists('verify_sign'))
{
    /**
     * 后台验证sign是否合法
     * @param $data
     * @param string $code
     * @return bool
     */
    function verify_sign($data, &$code='')
    {
        // 验证参数中是否有签名
        if (!isset($data['sign']) || !$data['sign']) {
            $code =  '10100';
            return false;
        }
        if (!isset($data['timestamp']) || !$data['timestamp']) {
            $code =  '10101';
            return false;
        }

        // 验证请求， 10分钟失效
        if (time() - $data['timestamp'] > 600) {
            $code =  '10102';
            return false;
        }

        /*验证签名*/
        $origin_sign = $data['sign'];
        // 客户端sign不参与校验，剔除
        unset($data['sign']);
        // 排序，按键名
        ksort($data);
        $params = http_build_query($data);
        $secret = config('secure.sign_secret');
        $sign = md5($params . $secret);
        if ($sign != $origin_sign) {
            $code = '10103';
            return false;
        }

        return true;
    }
}

if (! function_exists('is_mobile'))
{
    /**
     * 验证手机号
     * @param $value
     * @return bool
     */
    function is_mobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if($result)
        {
            return true;
        }
        return false;
    }
}

if (! function_exists('is_people_id'))
{
    /**
     * 严格判断身份证有效性
     * @param $id_card
     * @return bool
     */
    function is_people_id($id_card)
    {
        if(strlen($id_card) == 18)
        {
            return idcard_checksum18($id_card);
        }
        elseif((strlen($id_card) == 15))
        {
            $id_card = idcard_15to18($id_card);
            return idcard_checksum18($id_card);
        }else{
            return false;
        }
    }
}

if (! function_exists('idcard_checksum18'))
{
    /**
     * 18位身份证校验码有效性检查
     * @param $idcard
     * @return bool
     */
    function idcard_checksum18($idcard)
    {
        if(strlen($idcard) != 18)
        {
            return false;
        }

        $idcard_base = substr($idcard,0,17);  // 前17位主要号码
        $idcard_sex = strtoupper(substr($idcard,17,1)); // 性别号码

        if(idcard_verify_number($idcard_base) != $idcard_sex)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}

if (! function_exists('idcard_verify_number'))
{
    /**
     * 计算身份证校验码，根据国家标准GB 11643-1999
     * @param $idcard_base
     * @return bool|string
     */
    function idcard_verify_number($idcard_base)
    {
        if(strlen($idcard_base) != 17)
        {
            return false;
        }

        //加权因子
        $factor = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
        //校验码对应值
        $verify_number_list = array('1','0','X','9','8','7','6','5','4','3','2');
        $checksum = 0;

        for($i=0;$i<strlen($idcard_base);$i++)
        {
            // 变量每一位号码
            $checksum += substr($idcard_base,$i,1) * $factor[$i];
        }

        $mod=$checksum % 11;
        $verify_number = $verify_number_list[$mod];

        return $verify_number;
    }
}

if (! function_exists('idcard_15to18'))
{
    /**
     * 将15位身份证升级到18位
     * @param $idcard
     * @return bool
     */
    function idcard_15to18($idcard)
    {
        if(strlen($idcard) != 15)
        {
            return false;
        }
        else
        {
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            $special_code = substr($idcard,12,3);   // 最后三位

            if(array_search($special_code, array('996','997','998','999')) !== false)
            {
                $idcard=substr($idcard,0,6).'18'.substr($idcard,6,9);
            }
            else
            {
                $idcard=substr($idcard,0,6).'19'.substr($idcard,6,9);
            }
        }

        $idcard = $idcard.idcard_verify_number($idcard);

        return $idcard;
    }
}
