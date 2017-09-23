<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Http
 */
namespace NComponent\Http;
if (isset($_SESSION['sid']) && !is_null($_SESSION['sid'])){
    @session_id($_SESSION['sid']);
    @session_start();
}else{
    @session_start();
    $_SESSION['sid'] = @session_id();
}
/**
 * Class Session
 * @package NComponent\Http
 */
class Session {
    /**
     *  初始化
     * @param array $array
     * @return Session
     */
    private static $ins ;
    public static function init(){
        if ( self::$ins instanceof self){
            return self::$ins;
        }
        self::$ins = new self();

        return self::$ins;
    }

    /**
     * 初始化session组件
     */
    public function initSession(){
        ini_set('session.auto_start', 0);
        ini_set('session.cookie_domain', 'api.com');
        $sid = $_SESSION['sid'];
        $this->set('sid',$sid,1800);
    }
    /**
     * 设置session
     * @param $name `Session名称`
     * @param $value `session值`
     * @param int $time 超时时间(秒)
     */
    public function set($name,$value,$time = 1800){
        $_SESSION[$name] = $value;
        $_SESSION[$name.'_Expires'] = 0 === $time ? 0 : time() + $time;
    }
    /**
     * 获取Session值
     * @param $name
     * @return null
     */
    public function get($name){
        //检查Session是否已过期
        if(isset($_SESSION[$name.'_Expires'])){
            if ( 0 === $_SESSION[$name.'_Expires']){
                return $_SESSION[$name];
            }else{
                if ($_SESSION[$name.'_Expires']>time()){
                    return $_SESSION[$name];
                }else{
                    Session::clear($name);
                    return null;
                }
            }
        }else{
            Session::clear($name);
            return null;
        }
    }
    /**
     * 清除某一Session值
     * @param $name `Session名称`
     * @return bool
     */
    public function clear($name){
        if (!isset($_SESSION[$name])){
            return true;
        }
        unset($_SESSION[$name]);
        unset($_SESSION[$name.'_Expires']);
    }
    /**
     * 重置销毁Session
     */
    public function destroy(){
        unset($_SESSION);
        session_destroy();
    }

}

