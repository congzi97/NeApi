<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NComponent\Cache;

use NeApi\NeApiError;

/**
 * Class CacheMemcached
 * memcached 分布式缓存
 * @package NComponent\Cache
 */
class CacheMemcached {

    /**
     * @var \Memcached
     */
    static public $obj = '';
    static private $memcachedConfig = [];
    static private $ins = '';
    static public function ins($config = []){
        self::$memcachedConfig = $config;
        if (self::$ins instanceof self){
            return self::$ins;
        }
        self::$ins = new self();
        return self::$ins;
    }
    static private function main(){
        if (!class_exists('memcached')){
            return ['code'=>-1,'msg'=>'请开启memcached扩展'];
        }
        if (empty(self::$memcachedConfig)){
            return ['code'=>-1,'msg'=>'请配置memcached'];
        }
        try{
            self::$obj = new \Memcached();
            if (!self::$obj->addServers(self::$memcachedConfig)){
                throw new \MemcachedException('连接memcached失败');
            }
            return self::$obj;
        }catch (\MemcachedException $exception){
            NeApiError::error($exception);
            return ['code'=>-1,'msg'=>'连接memcached失败'];
        }
    }

    /**
     * 设置数据
     * @param $key
     * @param $value
     * @param int $exp
     * @return bool
     */
    public static function set($key,$value,$exp = 1800){
        $obj = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->add($key,$value,$exp);
    }
    /**
     * 获取数据
     * @param $key
     * @return mixed
     */
    public static function get($key){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->get($key);
    }

    /**
     * 删除数据
     * @param $key
     * @return bool
     */
    public static function del($key){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->delete($key);
    }

    /**
     * 添加数据但会覆盖原数据
     * @param $key
     * @param $value
     * @param int $exp
     * @return bool
     */
    public static function replace($key,$value,$exp = 1800){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->replace($key,$value,$exp = 1800);
    }

    /**
     * 清除所有缓存
     * @return bool
     */
    public static function flush(){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->flush();
    }

    /**
     * 缓存加法 比如 key的value为1 increment(key,5)后key的value为1+5 = 6
     * @param $key
     * @param $number
     * @return int
     */
    public static function increment($key,$number){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->increment($key,$number);
    }

    /**
     * 缓存减法 比如 key的value为1 decrement(key,5)后key的value为1-5 = -4
     * @param $key
     * @param $number
     * @return int
     */
    public static function decrement($key,$number){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->decrement($key,$number);
    }

    /**
     * 设置多条数据
     * @param array|null $key
     * @param int $exp
     * @return bool
     */
    public static function setMulti( ? array  $key,$exp = 60){
        $obj = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->setMulti($key,$exp);
    }
    /**
     * 获取多条数据
     * @param $key
     * @return mixed
     */
    public static function getMulti( ? array $key){
        $obj    = empty(self::$obj) ? self::main() : self::$obj;
        return $obj->getMulti($key);
    }



}
