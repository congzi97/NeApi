<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Http
 */
namespace NComponent\Http;
/**
 * Class Sign
 * 验证签名
 * @package NComponent\Http
 */
class Sign {
    private $sign = '';
    /**
     * 验证签名是否正确
     * @return bool
     */
    public function md5_sign($type = 'post'){
        $str = $this->getData($type);
        if (false == $str) {
            return false;
        }
        $md  = strtoupper(md5($str));
        if ( $md !== $this->sign){
            return false;
        }
        return true;
    }

    public function sha1_sign($type = 'post'){
        $str = $this->getData($type);
        if (false == $str) {
            return false;
        }
        $md  = strtoupper(sha1($str));
        if ( $md !== $this->sign){
            return false;
        }
        return true;
    }
    private function getData($type){
        if ( 'post' === strtolower($type)) {
            if (!isset($_POST['sign'])){
                return false;
            }
            $this->sign = $_POST['sign'];
            $data = \Ne::$app->request->getPostAll();
        }else{
            if (!isset($_GET['sign'])){
                return false;
            }
            $this->sign = $_GET['sign'];
            $data = \Ne::$app->request->getGetAll();
        }
        ksort($data);
        $str = '';
        foreach ($data as $key => $value){
            if ('sign' !== $key){
                $str .= $key.'='.$value.'&';
            }
        }
        $str = $str.'key='.ACCESS_TOKEN;
        return $str;
    }
}
