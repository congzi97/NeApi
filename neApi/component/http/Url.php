<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Http
 */
namespace NComponent\Http;

/**
 * Class Url
 * URL 控制类
 * @package NComponent\Http
 */
class Url {
    /**
     * Url constructor.
     * 获取路由、HTTP请求信息
     */
    public function __construct(){
        \Ne::$httpInfo = [
            'domain'    =>  $_SERVER['SERVER_NAME'],
            'ip'        =>  \Ne::$app->get->ip(),
        ];
        if (!empty($_SERVER['PATH_INFO'])){
            if ( true === $this->CheckFile()){
                \Ne::$app->customBefore->StringContainingSpot();
            }
            $_SERVER['PATH_INFO'] = substr($_SERVER['PATH_INFO'],1);
            $array = explode('/',$_SERVER['PATH_INFO']);
            if ('admin' === $array[0]){
                \Ne::$app->base = true;
            }
            if ( true === \Ne::$app->config['route']){
                if ( false == $routeInfo = $this->isRoute($_SERVER['PATH_INFO'])){
                    if ( false === \Ne::$app->config['routeNotExistence']){
                        \Ne::$app->AppEnd('API NOT FOUND ');
                    }
                }else{
                    return $this->route($routeInfo);
                }
            }
            return $this->notRoute($array);
        }else{
            $_SERVER['PATH_INFO']  =  \Ne::$app->config['pathInfoEmpty'];
            $array = explode('/',$_SERVER['PATH_INFO']);
            return $this->notRoute($array);
        }
    }
    /**
     * 非路由数据
     * @param array $array
     * @return bool
     */
    private function notRoute($array){
        if (isset($array[1])){
            if (strpos($array[1],'.')){
                $tmp = explode('.',$array[1]);
                $action = $tmp[0];
            }else{
                $action = $array[1];
            }
        }else{
            $action = '';
        }
        \Ne::$route = [
            'info'          =>  $_SERVER['PATH_INFO'],
            'time'          =>  $_SERVER['REQUEST_TIME'],
            'RoutingMode'   =>  true,
            'controller'    =>  $array[0],
            'action'        =>  $action,
            'login'         =>  false,
            'admin'         =>  false,
            'allowType'     =>  null,
        ];
        return true;
    }
    /**
     * 路由模式
     * @param $routeInfo
     * @return bool
     */
    private function route($routeInfo){
        $caArr =  explode('/',$routeInfo['CA']);
        \Ne::$route = [
            'info'          =>  $_SERVER['PATH_INFO'],
            'time'          =>  $_SERVER['REQUEST_TIME'],
            'RoutingMode'   =>  true,
            'controller'    =>  $caArr[1],
            'action'        =>  $caArr[0],
            'login'         =>  $routeInfo['login'],
            'admin'         =>  $routeInfo['admin'],
            'allowType'     =>  $routeInfo['method'],
        ];
        return true;
    }
    /**
     * 判断是否为路由访问
     * @param $name
     * @return bool | array
     */
    private function isRoute($name){
        if (isset(\Ne::$app->routes[$name])){
            $d = \Ne::$app->routes[$name];
            unset(\Ne::$app->routes);
            return $d;
        }
        return false;
    }

    /**
     * 检查是否带有.
     * @return bool
     */
    private function CheckFile(){
        if (strpos($_SERVER['PATH_INFO'],'.')){
            return true;
        }
        return false;
    }

}
