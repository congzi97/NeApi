<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Http
 */
namespace NComponent\Http;
/**
 * Class Request
 * @package NComponent\Http
 */
class Request {
    /**
     * 获取地址栏参数
     * @param $index
     * @param null $defaultValue
     * @return mixed|null
     */
    public function getParams($index , $defaultValue = null){
        $array = explode('/',$_SERVER['PATH_INFO']);
        if (!isset($array[$index])){
            return $defaultValue;
        }
        if (strpos($array[$index],'.')){
            $tmp = explode('.',$array[$index]);
            $value = \Ne::$app->xxs->string_remove_xss($tmp[0]);
            return $value;
        }
        $value = \Ne::$app->xxs->string_remove_xss($array[$index]);
        return $value;
    }
    private $AlreadyObtained = [];
    public $serverDecodeField = [];
    /**
     * 获取 $_GET数据
     * @param $name
     * @param string $defaultValue
     * @return mixed|string
     */
    public function get($name , $defaultValue = ''){
        if (isset($this->AlreadyObtained['GetAll'][$name])){
            return  $this->AlreadyObtained['GetAll'][$name];
        }
        if (isset($this->AlreadyObtained['Get'.$name])){
            return  $this->AlreadyObtained['Get'.$name];
        }
        if (!isset($_GET[$name])){
            return $defaultValue;
        }
        if (in_array($name,$this->serverDecodeField)){
            $_GET[$name] = \Ne::$app->crypt->de($_GET[$name]);
        }
        // 过滤 $_get值
        $value = \Ne::$app->xxs->string_remove_xss($_GET[$name]);
        $this->AlreadyObtained['Get'.$name] = $value;
        return $value;
    }

    /**
     * 获取 $_POST 数据
     * @param $name
     * @param string $defaultValue
     * @return mixed|string
     */
    public function post($name ,$defaultValue = ''){
        if (isset($this->AlreadyObtained['PostAll'][$name])){
            return  $this->AlreadyObtained['PostAll'][$name];
        }
        if (isset($this->AlreadyObtained['Post'.$name])){
            return  $this->AlreadyObtained['Post'.$name];
        }
        if (!isset($_POST[$name])){
            return $defaultValue;
        }
        if (in_array($name,$this->serverDecodeField)){
            $_POST[$name] = \Ne::$app->crypt->de($_POST[$name]);
        }
        // 过滤 $_POST值
        $value = \Ne::$app->xxs->string_remove_xss($_POST[$name]);
        $this->AlreadyObtained['Post'.$name] = $value;
        return $value;
    }

    /**
     * 获取全部POST 数据
     * @return array
     */
    public function getPostAll(){
        if (isset($this->AlreadyObtained['PostAll'])){
            return  $this->AlreadyObtained['PostAll'];
        }
        $array = array();
        $obj = \Ne::$app->xxs;
        foreach ($_POST as $key => $value){
            if (in_array($key,$this->serverDecodeField)){
                $value = \Ne::$app->crypt->de($value);
            }
            $value = $obj->string_remove_xss($value);
            $array[$key] = $value;
            $this->AlreadyObtained['PostAll'][$key] = $value;
        }
        return $array;
    }
    /**
     * 获取全部GET 数据
     * @return array
     */
    public function getGetAll(){
        if (isset($this->AlreadyObtained['GetAll'])){
            return  $this->AlreadyObtained['GetAll'];
        }
        $array = array();
        $obj = \Ne::$app->xxs;
        foreach ($_GET as $key => $value){
            if (in_array($key,$this->serverDecodeField)){
                $value = \Ne::$app->crypt->de($value);
            }
            $value = $obj->string_remove_xss($value);
            $array[$key] = $value;
            $this->AlreadyObtained['GetAll'][$key] = $value;
        }
        return $array;
    }

    /**
     * 是否是GET提交的
     */
    public function isGet(){
        return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
    }

    /**
     * 是否是POST提交
     * @return bool
     */
    public function isPost() {
        return $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
    }

    /**
     * 判断是否ajax访问
     * @return bool
     */
    public function isAjax(){
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            return true;
        }else{
           return false;
        }
    }

    /**
     * 请求远程
     * @param $url
     * @param null $data
     * @return mixed
     */
    public function http_request($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }





}
