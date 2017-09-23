<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NComponent\Image;

class Verify extends CreateCode {
    private static $ins ;
    public static function ins($config = []){
        if (self::$ins instanceof self){
            return self::$ins;
        }
        self::$ins = new self($config);
        return self::$ins;
    }
    private function __construct($config){
        \Ne::$app->VerifyConfig = $config;
        parent::Create();
    }

    /**
     * 判断验证码
     * @param $code
     * @return array
     */
    public function Check($code){
        $code = trim($code);
        if ( '' === $code || \Ne::$app->VerifyConfig['codeLength'] !== strlen($code)){
            return ['msg'=>'验证码为'.\Ne::$app->VerifyConfig['codeLength'].'位字符','code'=>-1];
        }
        $sessionObj = \Ne::$app->session;
        $code_value = $sessionObj->get('code_value');
        if ( null === $code_value){
            return ['msg'=>'验证码过期','code'=>-1];
        }
        if (strtolower($code_value) != strtolower($code) ){
            return ['msg'=>'验证码错误','code'=>-1];
        }
        return ['msg'=>'验证码正确','code'=>200];
    }
    /**
     * 获取验证码SRC
     * @return string
     */
    public function getSrc(){
        return SERVER_DOMAIN.'/'.md5('verify+'.time()).'.png?time='.time();
    }
    /**
     * 显示验证码
     */
    public function display(){
        // 生成验证码
        $code = $this->VerifyCreateCode();
        $sessionObj = \Ne::$app->session;
        $sessionObj->set('code_value',$code,\Ne::$app->VerifyConfig['expired'] * 60);
        if (isset(\Ne::$app->VerifyConfig['imgType']) || 'gif' === strtolower(\Ne::$app->VerifyConfig['imgType'])){
            parent::GIF_Image_Code($code);
        }
        parent::doImg($code);
    }

    /**
     * 创建验证码文本
     * @return string
     */
    private function VerifyCreateCode(){
        # 1为字母+数字 2纯字母 3纯数字
        $getObj = \Ne::$app->get;
        $length = \Ne::$app->VerifyConfig['codeLength'];
        switch (\Ne::$app->VerifyConfig['type']){
            case 1:
                return $getObj->rand($length);
                break;
            case 2:
                return $getObj->rand($length,0);
                break;
            case 3:
                return $getObj->rand($length,1);
                break;
            default:
                return $getObj->rand($length);
                break;
        }
    }

}
