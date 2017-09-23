<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NeApi;
use Custom\Before;
use Custom\Create;
use Custom\Event;
use NComponent\Cache\CacheMemcached;
use NComponent\Cache\CacheRedis;
use NComponent\Common\Arr;
use NComponent\Common\Check;
use NComponent\Common\Color;
use NComponent\Common\Crypt;
use NComponent\Common\Get;
use NComponent\Common\Time;
use NComponent\Common\Xml;
use NComponent\Common\Xxs;
use NComponent\Db\MySql;
use NComponent\Email\MyEmail;
use NComponent\File;
use NComponent\Http\Request;
use NComponent\Http\Session;
use NComponent\Http\Sign;
use NComponent\Http\Url;
use NComponent\Image\Picture;
use NComponent\Image\Verify;
use NComponent\Upload\Upload;

/**
 * Class Application
 * @property Upload $upload `上传组件
 * @property MyEmail $email `邮箱
 * @property \Custom\Before $customBefore `自定义功能
 * @property CacheRedis $redis `Redis缓存操作
 * @property CacheMemcached $memcached `memcached缓存
 * @property File\Operation $fileOperation 文件操作
 * @property Arr $arr   数据操作
 * @property Check $check 检查数据
 * @property Crypt $crypt 数据加密
 * @property Log $log 检查数据
 * @property Time $time 数据加密
 * @property Xxs $xxs 防xxs
 * @property Get $get 获取数据
 * @property Xml $xml   XML操作
 * @property Color $color   颜色转换
 * @property Request $request   request请求操作
 * @property Sign $sign         签名检查
 * @property Url $url   URL管理
 * @property Session $session   获取session对象
 * @property Verify $verify     验证码对象
 * @property Picture $picture   图片操作
 * @property MySql $mysql       数据库操作
 * @package NeApi
 */
class Application extends Component {
    // APP
    public static $app;
    /**
     *
     * @var array
     * info         URL信息
     * time         客户端发来的时间
     * routingMode  是否为路由模式
     * controller   控制器名称
     * action       方法
     * login        是否需要登录访问
     * admin        是否需要管理员访问
     * allowType    允许的请求方式
     */
    public static $route = [
        'info'          =>  '',
        'time'          =>  '',
        'RoutingMode'   =>  false,
        'controller'    =>  '',
        'action'        =>  '',
        'login'         =>  false,
        'admin'         =>  false,
        'allowType'     =>  'post',
    ];
    /**
     * @var array HTTP请求的信息
     * domain   域名
     * IP       客户端IP
     */
    public static $httpInfo = [
        'domain'    =>  '',
        'ip'        =>  '',
    ];
    // api配置
    public $config      = [];
    // 输出消息
    public $msg         = '';
    // 输出错误代码
    public $code        = -1;
    // 输出数据
    public $data        = [];
    // 组件
    public $Component   = [];
    public $ObjectArr   = [];
    public $routes      = [];
    public $sid         = '';
    public $base        = false;
    public $error       = false;
    public $errorInfo   = '';
    public function __construct($config = [],$component = [] , $routes = []){
        self::$app          = $this;
        $this->config       = $config;
        $this->Component    = $component;
        $this->routes       = $routes;
        $this->Initialization();
        if ( false  === \Ne::$app->base ){
            $res = \Ne::$app->customBefore->BeforeCreateController();
        }
        if (isset($res['controller']) && !empty($res['controller']) ){
            self::$route['controller'] = $res['controller'];
        }
        if (isset($res['action']) && !empty($res['action']) ){
            self::$route['action'] = $res['action'];
        }
        if ( true === \Ne::$app->base){
            $action = ucfirst(strtolower(self::$route['action']));
        }else if ( true === \Ne::$app->config['ActionFirstCapital']){
            $action = ucfirst(strtolower(self::$route['action']));
        }else{
            $action = self::$route['action'];
        }
        if ( true === \Ne::$app->base){
            $controller = ucfirst(strtolower(self::$route['controller']));
        }else if ( true === \Ne::$app->config['CMFirstCapital']){
            $controller = ucfirst(strtolower(self::$route['controller']));
        }else{
            $controller = self::$route['controller'];
        }
        self::$route['controller'] = $controller;
        self::$route['action'] = $action;
        if ( true === \Ne::$app->base ){
            $this->baseApp();
        }else{
            $this->createAppController();
        }
    }
    /**
     * 创建控制器
     */
    private function createAppController(){
        $array['class'] = $this->config['AppNamespace'].'\Controller\\'.self::$route['controller'];
        $array['action'] = self::$route['action'];
        \Ne::$app->createAppComponent($array);
    }
    /**
     * APP初始化执行
     */
    private function Initialization(){
        if (isset($_REQUEST['serverDecodeField'])){
            \Ne::$app->request->serverDecodeField = explode(',',\Ne::$app->crypt->de($_REQUEST['serverDecodeField']));
        }
        // 初始化 url 组件
        \Ne::$app->url;
        // 初始化 session 组件
        \Ne::$app->session->initSession();
    }
    /**
     * 官方APP应用
     */
    private function baseApp(){
        try {
            $res = \Ne::$app->createAppComponent([
                'class'     => 'BaseApp\Controller\\'.self::$route['controller'],
                'action'    => 'Action'.self::$route['action']
            ]);
            if ( false === $res ){
                throw new NeApiError('创建控制器失败');
            }
        }catch (NeApiError $exception){
            NeApiError::error($exception);
        }
    }
    /**
     * APP执行结束
     * @param string $msg
     * @param int $code
     * @param array $data
     */
    public function AppEnd( $msg = '' , $code = -1 , $data = [] ){
        $eTime=microtime(true);
        $total=$eTime-START_TIME;
        $array['msg']   = '' === $msg           ? $this->msg    : $msg;
        $array['code']  = '' === $code          ? $this->code   : $code;
        $array['data']  = 0  === count($data)   ? $this->data   : $data;
        $array['time']  = $total;
        echo json_encode($array);
//        print_r($array);
//        $thistime = round($total,3);
//        echo '<br />本次执行'.$thistime.'/秒';
        exit();
    }
}