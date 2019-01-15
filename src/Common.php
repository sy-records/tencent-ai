<?php
/**
 * author: ShenYan.
 * Email：52o@qq52o.cn
 * CreatedTime: 2019/1/15 20:50
 */

namespace SyRecords\TencentAi;

class Common
{
    /**
     * 接口鉴权, 获取签名
     * @param $params
     * @param $appkey
     *
     * @return string
     */
    protected static function getReqSign($params, $appkey)
    {
        ksort($params);

        $str = '';
        foreach ($params as $key => $value) {
            if ($value !== '') {
                $str .= $key . '=' . urlencode($value) . '&';
            }
        }

        $str .= 'app_key=' . $appkey;

        $sign = strtoupper(md5($str));
        return $sign;
    }

    /**
     * 发起请求
     * @param      $url
     * @param bool $params
     * @param int  $isPost
     *
     * @return mixed|string
     */
    protected static function doCurl($url, $params = false, $isPost = 0)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/x-www-form-urlencoded;'));
        if ($params) {
            $body = http_build_query($params);
        } else {
            $body = false;
        }
        if ($isPost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($body) {
                curl_setopt($ch, CURLOPT_URL, $url.'?' . $body);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === false) {
            return 'cURL Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $response;
    }
}
