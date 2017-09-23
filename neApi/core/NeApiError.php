<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NeApi;
use Throwable;
use \Whoops;
class NeApiError extends \Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    public static function error($e){
        $whoops = new  Whoops\Run;
        $handler = new Whoops\Handler\PrettyPageHandler();
        $handler->setPageTitle($e->getMessage());//设置报错页面的title
        $whoops->pushHandler($handler);
        $whoops->register();
        exit($e->getMessage());
    }

}
