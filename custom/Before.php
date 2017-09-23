<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace Custom;

class Before {
    /**
     * 创建APP控制器之后
     */
    public function AfterCreateAPPController(){

    }
    /**
     * 创建控制器之前执行的函数
     * 可以映射URL
     * @return array | bool ['controller'=>'新的控制器名称','action'=>'新的方法名称'];
     */
    public function BeforeCreateController(){
        /**
         * $route
         * info         URL信息
         * time         客户端发来的时间
         * routingMode  是否为路由模式
         * controller   控制器名称
         * action       方法
         * login        是否需要登录访问
         * admin        是否需要管理员访问
         * allowType    允许的请求方式
         */
        $route = \Ne::$route;

        return false;
    }
    /**
     * URL含有 . 会先执行该函数
     */
    public function StringContainingSpot(){
        $imgArray = ['jpg','jpeg','gif','png','icon'];
        if (in_array(pathinfo($_SERVER['PATH_INFO'],PATHINFO_EXTENSION),$imgArray)){
            return $this->display_image(pathinfo($_SERVER['PATH_INFO'],PATHINFO_FILENAME));
        }
        return false;
    }
    /**
     * 其它文件，比如下载文件等...
     * @param $array
     */
    private function download_file($array){

    }
    /**
     * 图片显示操作
     * @param $name
     * @return bool
     */
    private function display_image($name){
        if ( md5('verify+'.\Ne::$app->request->get('time')) === $name){
            \Ne::$app->verify->display();
            // 也可以在这里中断操作
            exit;
        }
        return false;
    }
}
