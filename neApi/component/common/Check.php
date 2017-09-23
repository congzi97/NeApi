<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\common
 */
namespace NComponent\Common;

class Check {
    /**
     * 是否为空值
     * @param string | array $value  `需要判断的字符
     * @return bool  返回true[为空、不存在、false...] 返回false [非空、存在...]
     */
    public function isEmpty($value){
        if (is_string($value)){
            $value = trim($value);
            return empty($value) ? true : false;
        }
        if (!is_array($value)){
            return true;
        }
        foreach ($value as $key => $val){
            $val = trim($val);
            if (empty($val)){
                return true;
            }
        }
        return false;
    }
    /**
     * 数字验证
     * param:$flag : int是否是整数，float是否是浮点型
     */
    public function isNum($str,$flag = 'float'){
        if(!$this->isEmpty($str)) return false;
        if(strtolower($flag) == 'int'){
            return ((string)(int)$str === (string)$str) ? true : false;
        }else{
            return ((string)(float)$str === (string)$str) ? true : false;
        }
    }
    /**
     * 手机号码验证
     * @param $str
     * @return bool
     */
    public function isMobile($str){
        $exp = "/^13[0-9]{1}[0-9]{8}$|15[012356789]{1}[0-9]{8}$|18[012356789]{1}[0-9]{8}$|14[57]{1}[0-9]$/";
        if(preg_match($exp,$str)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * URL验证，纯网址格式，不支持IP验证
     */
    public function isUrl($str){
        if(!$this->isEmpty($str)) return false;
        return preg_match('/(http|https|ftp|ftps)://([w-]+.)+[w-]+(/[w-./?%&=]*)?/i',$str) ? true : false;
    }
    /**
     * 检查是否是时间格式
     * @param $dateTime
     * @return bool
     */
    public function time($dateTime){
        $ret = strtotime($dateTime);
        return $ret !== FALSE;
    }
    /**
     * 判断某个数字是否在区间内
     * 例: [0,10] (1,10) (0,10] [1,10)
     * @param $str
     * @param $int
     * @return bool
     */
    public function intBetween($str,$int){
        if (is_string($str) == false){
            $str = strval($str);
        }
        if (preg_match('/\[([0-9]+),([0-9]+)\]/',$str) == true){
            $str = ltrim($str,'[');
            $str = rtrim($str,']');
            $arr = explode(',',$str);
            return $arr[0] <= $int && $int <= $arr[1];
        }
        if (preg_match('/\(([0-9]+),([0-9]+)\)/',$str) == true){
            $str = ltrim($str,'(');
            $str = rtrim($str,')');
            $arr = explode(',',$str);
            return $arr[0] < $int && $int < $arr[1];
        }
        if (preg_match('/\(([0-9]+),([0-9]+)\]/',$str) == true){
            $str = ltrim($str,'(');
            $str = rtrim($str,']');
            $arr = explode(',',$str);
            return $arr[0] < $int && $int <= $arr[1];
        }
        if (preg_match('/\[([0-9]+),([0-9]+)\)/',$str) == true){
            $str = ltrim($str,'[');
            $str = rtrim($str,')');
            $arr = explode(',',$str);
            return $arr[0] <= $int && $int < $arr[1];
        }

        return false;
    }
    /**
     * 判断邮箱格式
     * @param $email
     * @return bool
     */
    public function email($email){
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 检查密码
     * @param $str
     * @param int $minLength
     * @param int $maxLength
     * @return bool
     */
    public function password($str,$minLength = 6 ,$maxLength = 16){
        if ($str == '')
            return false;
        $res  =  preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9a-zA-Z]{'.$minLength.','.$maxLength.'}$/',$str);
        return $res == 1 ? true : false;
    }
    /**
     * 检测上传文件格式是否允许
     * @param $type
     * @param $fileName
     * @return bool
     */
    public function fileType($type,$fileName){
        if (strstr($fileName,'.') == false)
            return false;
        $arr = explode('.',$fileName);
        $fileType = strtolower(end($arr));
        if (strstr($type,',') == false){
            if (strtolower($type) == $fileType)
                return true;
            else
                return false;
        }else{
            $arr2 = explode(',',$type);
            for ($i = 0; $i < count($arr2); $i++){
                if (strtolower($arr2[$i]) == $fileType){
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * 判断是否是全中文
     * @param $str
     * @return bool
     */
    public function isAllChinese($str){
        if(!preg_match("[^\x80-\xff]","$str")){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 判断是否包含中文
     * @param $str
     * @return bool
     */
    public function isChinese($str){
        if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $str, $match)) {
            return true;
        } else {
            return false;
        }
    }
}
