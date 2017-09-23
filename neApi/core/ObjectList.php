<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NeApi;

use NComponent\Cache\CacheMemcached;
use NComponent\Cache\CacheRedis;
use NComponent\Common\Arr;
use NComponent\Common\Check;
use NComponent\Common\Color;
use NComponent\Common\Crypt;
use NComponent\Common\Get;
use NComponent\Common\Xml;
use NComponent\Common\Xxs;
use NComponent\Db\MySql;
use NComponent\Email\MyEmail;
use NComponent\File\Operation;
use NComponent\Http\Request;
use NComponent\Http\Session;
use NComponent\Http\Sign;
use NComponent\Http\Url;
use NComponent\Image\Picture;
use NComponent\Image\Verify;
use NComponent\Upload\Upload;

class ObjectList {
    /**
     * 上传组件
     * @param $data
     * @return Upload
     */
    public function upload($data){
        return \Ne::$app->CreateComponentData('upload',$data);
    }
    /**
     * 邮箱组件
     * @param $data
     * @return MyEmail
     */
    public function email($data){
        return \Ne::$app->CreateComponentData('email',$data);
    }
    /**
     * redis 缓存
     * @param $data
     * @return CacheRedis
     */
    public function c_redis($data){
        return \Ne::$app->CreateComponentData('redis',$data);
    }

    /**
     * memcached缓存
     * @param $data
     * @return CacheMemcached
     */
    public function c_memcached($data){
        return \Ne::$app->CreateComponentData('memcached',$data);
    }

    /**
     * 文件的一些操作
     * @param $data
     * @return Operation
     */
    public function fileOperation($data){
        return \Ne::$app->CreateComponentData('fileOperation',$data);
    }

    /**
     * 数据库操作
     * @param $data
     * @return MySql
     */
    public function mysql($data){
        return \Ne::$app->CreateComponentData('mysql',$data);
    }

    /**
     * 图片操作
     * @param $data
     * @return Picture
     */
    public function picture($data){
        return \Ne::$app->CreateComponentData('picture',$data);
    }

    /**
     * 验证码类
     * @param $data
     * @return Verify
     */
    public function verify($data){
        return \Ne::$app->CreateComponentData('verify',$data);
    }

    /**
     * session
     * @param $data
     * @return Session
     */
    public function session($data){
        return \Ne::$app->CreateComponentData('session',$data);
    }
    /**
     * url
     * @param $data
     * @return Url
     */
    public function url($data){
        return \Ne::$app->CreateComponentData('url',$data);
    }
    /**
     * 验证数据
     * @param $data
     * @return Sign
     */
    public function sign($data){
        return \Ne::$app->CreateComponentData('sign',$data);
    }
    /**
     * 请求数据处理
     * @param $data
     * @return Request
     */
    public function request($data){
        return \Ne::$app->CreateComponentData('request',$data);
    }
    /**
     * color颜色处理
     * @param $data
     * @return Color
     */
    public function color($data){
        return \Ne::$app->CreateComponentData('color',$data);
    }
    /**
     * XML操作
     * @param $data
     * @return Xml
     */
    public function xml($data){
        return \Ne::$app->CreateComponentData('xml',$data);
    }
    /**
     * 获取一些数据
     * @param $data
     * @return Get
     */
    public function get($data){
        return \Ne::$app->CreateComponentData('get',$data);
    }
    /**
     * xxs攻击处理
     * @param $data
     * @return Xxs
     */
    public function xxs($data){
        return \Ne::$app->CreateComponentData('xxs',$data);
    }
    /**
     * 数据加密
     * @param $data
     * @return Crypt
     */
    public function crypt($data){
        return \Ne::$app->CreateComponentData('crypt',$data);
    }
    /**
     * 检查数据
     * @param $data
     * @return Check
     */
    public function check($data){
        return \Ne::$app->CreateComponentData('check',$data);
    }
    /**
     * 数组操作
     * @param $data
     * @return Arr
     */
    public function arr($data){
        return \Ne::$app->CreateComponentData('arr',$data);
    }
}