<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NeApi;


class Component extends Object {

    /**
     * 设置组件
     * @param $name
     * @param $value
     */
    public function __set($name, $value){
        \Ne::$app->$name = $value;
    }
    /**
     * 获取组件
     * @param $name
     * @return mixed
     */
    public function __get($name){
        $getName = 'get'.ucfirst($name);
        if (isset($this->$getName)){
            return $this->$getName;
        }
        try {
            $res =  parent::createComponent($name,static::$app->Component[$name]);
            if ( empty($res)){
                throw new NeApiError('初始化组件'.$name.'错误');
            }
            $this->$getName = $res;
            return $res;
        }catch (NeApiError $exception){
            NeApiError::error($exception);
        }
    }

    /**
     * 注入配置 创建组件
     * @param $name
     * @param $data
     * @return mixed
     */
    public function CreateComponentData($name,$data){
        $getName = 'get'.ucfirst($name);
        if (isset($this->$getName)){
            return $this->$getName;
        }
        try {
            foreach ($data as $key => $value){
                static::$app->Component[$name]['config'][$key] = $value;
            }
            $res =  parent::createComponent($name,static::$app->Component[$name]);
            if ( empty($res)){
                throw new NeApiError('初始化组件'.$name.'错误');
            }
            $this->$getName = $res;
            return $res;
        }catch (NeApiError $exception){
            NeApiError::error($exception);
        }
    }
}

