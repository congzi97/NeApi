<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NeApi;
class BaseNeApi extends Application {
    public function run(){
    }
    /**
     * 结束程序
     * @param string $msg
     * @param int $code
     * @param array $data
     */
    public function end($msg = '' , $code = -1 , $data = []){
        parent::AppEnd($msg , $code, $data );
    }
}
