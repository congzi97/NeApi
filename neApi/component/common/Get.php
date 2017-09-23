<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\common
 */
namespace NComponent\Common;

class Get {
    /**
     * 获取随机小数点
     * @param int $min
     * @param int $max
     * @return float|int
     */
    public function randomFloat($min = 0, $max = 1) {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }
    /**
     * 获取随机字符串
     * @param int $length
     * @param int $type
     * @return string
     */
    public function rand($length = 4,$type = 2){
        $str = [
            'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            '0123456789',
            'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        ];
        $str = isset($str[$type]) ? $str[$type] : $str[2];
        $count = strlen($str);
        $randData = '';
        for ($i = 0 ; $i < $length;$i++){
            $randData .= $str[rand(0,$count - 1)];
        }
        return $randData;
    }

    /**
     * 上传文件时获取错误信息
     * @param $number
     * @return string
     */
    public function uploadError($number){
        switch ($number){
            case 1:
                return '上传的文件大于服务器限制的值';
                break;
            case 2:
                return '上传文件的大小超过了HTML表单MAX_FILE_SIZE选项指定的值';
                break;
            case 3:
                return '文件只有部分被上传';
                break;
            case 4:
                return '没有文件被上传';
                break;
            case 6:
                return '没有指定临时文件夹';
                break;
            default:
                return  '未知错误';
                break;
        }
    }

    /**
     * 获取字符串长度，支持中英文
     * @param $str
     * @return int
     */
    public function strLength($str){
        preg_match_all("/./us", $str, $matches);
        return count(current($matches));
    }
    /**
     * 获取 文件后缀
     * @param $filename
     * @return mixed
     */
    public function fileHZ($filename){
        return pathinfo($filename,PATHINFO_EXTENSION);
    }

    /**
     * 获取 设备 操作系统
     * @return string
     */
    public function USER_AGENT(){
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $_is_pc = (strpos($agent, 'windows')) ? true : false;
        $_is_mac = (strpos($agent, 'Mac')) ? true : false;
        $_is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $_is_ipad = (strpos($agent, 'ipad')) ? true : false;
        $_is_android = (strpos($agent, 'android')) ? true : false;
        if($_is_pc)
            return 'Windows';
        if($_is_mac)
            return 'Mac';
        if($_is_iphone)
            return 'iPhone';
        if($_is_ipad)
            return 'iPad';
        if($_is_android)
            return 'Android';
        return 'Unknown';
    }

    /**
     * 返回浏览器名称
     * @return string
     */
    public function getBrowser(){
        if(!empty($_SERVER['HTTP_USER_AGENT']))
        {
            $br = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/MSIE/i',$br)){
                $br = 'MSIE';
            }
            elseif (preg_match('/Firefox/i',$br)){
                $br = 'Firefox';
            }elseif (preg_match('/Chrome/i',$br)){
                $br = 'Chrome';
            }elseif (preg_match('/Safari/i',$br)){
                $br = 'Safari';
            }elseif (preg_match('/Opera/i',$br)){
                $br = 'Opera';
            }else {
                $br = 'Other';
            }
            return $br;
        }else
            return "Chrome";
    }

    /**
     * 获取客户端,及浏览器所在的电脑的ip地址
     * @return mixed
     */
    public function ip() {
        $unknown = 'unknown';
        $ip = '';
        if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown) ) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif ( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown) ) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (false !== strpos($ip, ','))
            $ip = reset(explode(',', $ip));
        return $ip;
    }
}