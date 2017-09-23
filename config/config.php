<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package config
 */

return [
    // APP命名空间
    'AppNamespace'  => 'app',
    // 为控制器方法添加前缀，留空即不添加
    'ActionPrefix'  => 'Action',
    // 方法名称首字母为大写
    'ActionFirstCapital'  =>  true,
    // 控制器/模型名称首字母为大写
    'CMFirstCapital'  =>  true,
    // 是否开启路由
    'route'         => true,
    // 路由不存在，是否继续执行
    'routeNotExistence' =>  true,
    'pathInfoEmpty' => 'index/index',
];
