<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
## 显示错误
error_reporting(E_ALL);
ini_set('display_errors',true);
#获取程序开始执行的时间
define('START_TIME',microtime(true));
## 加载类库
require  '../vendor/autoload.php';
## 加载define常量
require __DIR__ . '/../config/define.php';
## 加载配置
$config     =  include __DIR__ . '/../config/config.php';
$component  =  include __DIR__ . '/../neApi/component/list.php';
$routes     =  true === $config['route'] ? include __DIR__ . '/../config/route.php' : [];
require __DIR__ . '/../neApi/NeApi.php';
(new Ne($config,$component,$routes))->run();
//try {
//    $whoops = new \Whoops\Run();
//    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
//    $whoops->register();
//} catch (Exception $e) {
//    echo $e->getMessage();
//}
//file();
