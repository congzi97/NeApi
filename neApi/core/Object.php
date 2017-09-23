<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NeApi;

/**
 * Class Object
 * @package NeApi
 */
class Object  extends ObjectList {
    public function exist($name){

    }

    /**
     * 创建APP组件
     * @param $array
     * @return mixed
     * @throws NeApiError
     */
    public function createAppComponent($array){
        if ( true === \Ne::$app->base ){
            $action     = $array['action'];
        }else{
            $action     = \Ne::$app->config['ActionPrefix'].$array['action'];
        }
        $controller = $array['class'];
        try{
            if (!class_exists($controller)){
                throw new NeApiError('NOT FOUND CLASS '.$controller );
            }
            if (!method_exists($controller,$action)){
                throw new NeApiError('NOT FOUND CLASS '.strtoupper($controller).'->Action ');
            }
            $obj  =  new $controller;
            $data =  $obj->$action();
            \Ne::$app->customBefore->AfterCreateAPPController();
            return $data;
        }catch (NeApiError $exception){
            NeApiError::error($exception);
        }
    }
    /**
     * 创建组件 or 对象
     * @param $name
     * @param $ComponentArray
     * @return mixed
     */
    public function createComponent($name,$ComponentArray){
        if (in_array($name,static::$app->Component)){
            return static::$app->Component[$name];
        }
        if (!is_array($ComponentArray)){
            return self::CreateObject($ComponentArray);
        }
        $class  = isset($ComponentArray['class'])  ? $ComponentArray['class']  : [];
        $action = isset($ComponentArray['action']) ? $ComponentArray['action'] : false;
        $config = isset($ComponentArray['config']) ? $ComponentArray['config'] : [];
        $static = isset($ComponentArray['static']) ? $ComponentArray['static'] : false;
        try {
            if (!class_exists($class)){
                throw new NeApiError('NOT FOUND CLASS '.$class );
            }
            if ( false === $action){
                return self::CreateObject($class,$config);
            }
            if (!method_exists($class,$action)){
                throw new NeApiError('NOT FOUND CLASS '.$class.' Action ');
            }
            if ( true === $static){
                return self::CreateObjectAndAction($class,$action,$config);
            }
            $class = new $class();
            return self::CreateObjectAndAction($class,$action,$config);
        }catch (NeApiError $exception){
            NeApiError::error($exception);
        }

    }

    /**
     * 实例化对象
     * @param $class
     * @param array $config
     * @return self
     */
    private function CreateObject($class,$config = []){
        $obj = new $class(!empty($config ) ? $config : []);
        return $obj;
    }

    /**
     * new self
     * @param $class
     * @param $action
     * @param array $config
     * @return mixed
     * @throws NeApiError
     */
    private function CreateObjectAndAction($class,$action,$config = []){
        return call_user_func(array($class,$action),$config);
    }
}

