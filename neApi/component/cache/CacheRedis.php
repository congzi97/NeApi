<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NComponent\Cache;
/**
 * Class CacheRedis
 * @property \Redis redis 缓存操作
 * @package NComponent\Cache
 */
class CacheRedis {
    /**
     * @var \Redis
     */
    static private $obj ;
    static private $config = [];
    static private $ins = '';
    /**
     * @var \Redis
     */
    static public  $redisConn;
    static public function ins($config = []){
        self::$config = $config;
        if (self::$ins instanceof self){
            return self::$ins;
        }
        self::$ins = new self();
        return self::$ins;
    }
    private function __construct(){
        $this->init();
    }
    /**
     * 连接Redis
     */
    private function init(){
        if (self::$obj instanceof \Redis){
            return self::$obj;
        }
        $redis = new \Redis();
        $result = $redis->connect(self::$config['host'],self::$config['port']);
        if (false === $result){
            return false;
        }
        self::$redisConn = $redis;
        self::$obj = $redis;
    }

    /**
     * 设置 key => value
     * @param $key          `key值
     * @param $value        `value值
     * @param int $timeout  `timeout过期时间
     * @return bool
     */
    public function set($key , $value , $timeout = 1800){
        if ( true === \Ne::$app->check->isEmpty([$key,$value]) ){
            return false;
        }
        return self::$obj->set($key,$value, $timeout );
    }

    /**
     * 获取缓存
     * @param $key
     * @return bool|string
     */
    public function get($key){
        if ( true === \Ne::$app->check->isEmpty($key) ){
            return false;
        }
        return self::$obj->get($key);
    }

    /**
     * 删除key缓存
     * @param $key
     * @return bool|int
     */
    public function del($key){
        if ( true === \Ne::$app->check->isEmpty($key) ){
            return false;
        }
        return self::$obj->del($key);
    }

    /**
     * 验证指定的键是否存在
     * @param $key
     * @return bool
     */
    public function exists($key){
        if ( true === \Ne::$app->check->isEmpty($key) ){
            return false;
        }
        return self::$obj->exists($key);
    }

    /**
     * 数字递增存储键值键 , 不存在的时候创建
     * @param $key
     * @return bool|int
     */
    public function increment($key){
        if ( true === \Ne::$app->check->isEmpty($key) ){
            return false;
        }
        if ( false === $this->exists($key)){
            return $this->set($key,1);
        }
        return self::$obj->incr($key);
    }

    /**
     * 数字递减存储键值。
     * @param $key
     * @return bool|int
     */
    public function decrement($key){
        if ( true === \Ne::$app->check->isEmpty($key) ){
            return false;
        }
        if ( false === $this->exists($key)){
            return $this->set($key,1);
        }
        return self::$obj->decr($key);
    }

    /**
     * 根据数组获取数据
     * @param $arrayKey
     * @return array|bool
     */
    public function toArrayGetKey($arrayKey){
        if ( true === \Ne::$app->check->isEmpty($arrayKey) ){
            return false;
        }
        return self::$obj->getMultiple($arrayKey);
    }

    /**
     * 设置 list列表缓存
     * @param $array
     * @return bool
     */
    public function setList( $array ){
        foreach ($array as $key => $value){
            for ($i = 0; $i < count($array[$key]);$i++){
                self::$obj->lPush($key,$array[$key][$i]);
            }
        }
        return true;
    }

    /**
     * 获取列表
     * @param $key
     * @param int $start `开始取值位置
     * @param int $end   `结束取值位置
     * @return bool | array
     */
    public function getList( $key, $start = 0 , $end = 10 ){
        if ( true === \Ne::$app->check->isEmpty($key) ){
            return false;
        }
        if ( false === $this->exists($key)){
            return false;
        }
        $list = self::$obj->lRange( $key, $start, $end );
        return $list;
    }

    /**
     * 根据数组获取缓存
     * @param $array
     * @return array
     */
    public function toArrayGetLists( $array ){
        # key => [0,10]
        $newArray = array();
        foreach ($array as $key => $value){
            if ( true === $this->exists($key)){
                $start = isset($value[0]) ? $value[0] : 0;
                $end   = isset($value[1]) ? $value[1] : 10;
                $newArray[$key] = $this->getList($key,$start,$end);
            }
        }

        return $newArray;
    }

    /**
     * 获取所有 KEY
     * @return array
     */
    public function getKeys(){
        return self::$obj->keys('*');
    }

    /**
     * 向列表的头部、尾部添加字符串值
     * @param $key
     * @param $value
     * @param string $mod   r 尾部 l头部
     * @return bool|int
     */
    public function rlPush($key,$value,$mod = 'r'){
        if ( true === \Ne::$app->check->isEmpty([$key,$value]) ){
            return false;
        }
        if ( 0 == $this->getListSize($key)){
            return false;
        }
        $mod = strtolower($mod);
        if ( 'l' === $mod){
            return self::$obj->rPush($key,$value);
        }if ( 'l' === $mod){
            return self::$obj->lPush($key,$value);
        }else{
            return false;
        }

    }

    /**
     * 获取列表的长度。如果列表不存在或为空，该命令返回0。如果该键不是列表，该命令返回FALSE
     * @param $key
     * @return bool | int
     */
    public function getListSize($key){
        if ( true === \Ne::$app->check->isEmpty($key) ){
            return false;
        }
        $int = self::$obj->lLen($key);
        return $int;
    }

    /**
     * 根据index操作list  赋新的值、获取值
     * @param $key
     * @param int $index
     * @param string $mod      get 和 set
     * @param string $value    set时必须设置此项
     * @return bool|String
     */
    public function indexKey($key,$index = 0,$mod = 'get',$value = ''){
        if ( true === \Ne::$app->check->isEmpty($key) ){
            return false;
        }
        if ( 0 == $this->getListSize($key)){
            return false;
        }
        if ( 'get' === $mod){
            return self::$obj->lIndex($key,$index);
        }
        if ( '' === $value){
            return false;
        }
        return self::$obj->lSet($key,$index,$value);
    }


}