<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
return [
    'arr'           =>  'NComponent\Common\Arr',
    'check'         =>  'NComponent\Common\Check',
    'crypt'         =>  'NComponent\Common\Crypt',
    'xxs'           =>  'NComponent\Common\Xxs',
    'get'           =>  'NComponent\Common\Get',
    'xml'           =>  'NComponent\Common\Xml',
    'color'         =>  'NComponent\Common\Color',
    'log'           =>  'NComponent\Common\Log',
    'time'          =>  'NComponent\Common\Time',
    'request'       =>  'NComponent\Http\Request',
    'sign'          =>  'NComponent\Http\Sign',
    'url'           =>  'NComponent\Http\Url',
    'session'       =>  'NComponent\Http\Session',
    'verify'        =>  ['class'=>'NComponent\Image\Verify','static'=>true,'action'=>'ins','config'=>[
        'width'         =>  100,
        'height'        =>  30,
        'font'          =>  '', // 字体路径
        'fontSize'      =>  20,
        'type'          =>  1,// [1,2,3]1为字母+数字 2纯字母 3纯数字
        'angle'         =>  0.5, // 角度
        'imgType'       =>  'gif', // GIF验证码
        'codeLength'    =>  4, // 验证码长度
        'expired'       =>  2, // 过期时间 单位 分钟
    ]],
    'picture'   =>  'NComponent\Image\picture',
    'mysql'     =>  ['class'=>'NComponent\Db\MySql','action'=>'ins'],
    'fileOperation'     => 'NComponent\File\Operation',
    'memcached'    =>  ['class'=>'NComponent\Cache\CacheMemcached','action'=>'ins',
        'config'=>[
        [
            'host'  =>  '127.0.0.1',
            'port'  =>  11211,
        ]
    ]],
    'redis'         =>  ['class'=>'NComponent\Cache\CacheRedis','action'=>'ins',
        'config'=>[
        'host'  =>  '127.0.0.1', 'port'  =>  6379,
    ]],
    'cycle'         =>  'NeApi\Cycle',
    'customBefore'  =>  'Custom\Before',
    'email'         =>  'NComponent\Email\MyEmail',
    'upload'        =>  ['class'=>'NComponent\Upload\Upload','static'=>true,'action'=>'init','config'=>[
        'allowType'=>'zip,rar,7z,jpg,jpeg,png,gif',
        'maxSize'=>2024, // 单个文件最大为/单位kb
    ]],
];
