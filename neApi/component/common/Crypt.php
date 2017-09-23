<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\common
 */
namespace NComponent\Common;
/**
 * Class Crypt
 * @property Crypt en `加密
 * @property Crypt de `解密
 * @package NComponent\Common
 */
class Crypt {
    /**
     * 加密
     * @param $value
     * @return bool|string
     */
    public function en($value){
        // 获取公匙
        $pub_key = openssl_get_publickey(file_get_contents(PEM_DIR.'/pub.pem', 'r'));
        if(!openssl_public_encrypt($value,$value,$pub_key)) {
            return false;
        }
        return base64_encode($value);
    }

    /**
     * 解密
     * @param $value
     * @return string
     */
    public function de($value){

        $pri_key = openssl_get_privatekey(file_get_contents(PEM_DIR.'/pri.pem', 'r'));
        if (openssl_private_decrypt(base64_decode($value), $decrypted, $pri_key))
            $data = $decrypted;
        else
            $data = '';
        return $data;
    }

    /*
     * password_hash(str, PASSWORD_DEFAULT) – 对密码加密.
     * password_verify(str,之前加密过的字符串) – 验证已经加密的密码，检验其hash字串是否一致.
     * password_needs_rehash(str, PASSWORD_DEFAULT) – 给密码重新加密.
     * password_get_info() – 返回加密算法的名称和一些相关信息.
     */
    public function Hash_Act($str,$options = 'ENCODE',$encode = false) {
        switch ($options){
            case 'ENCODE':
                return password_hash($str, PASSWORD_DEFAULT);
                break;
            case 'DECODE':
                return password_verify($str,$encode);
                break;
        }
    }
}
