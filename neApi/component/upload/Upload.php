<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NComponent\Upload;

class Upload {
    private $config = [];
    /**
     * @var self
     */
    private static $ins = '';
    private $size = '';
    private $type = '';
    public static function init($config){
        if (self::$ins instanceof self){
            return self::$ins;
        }
        $_self = new self($config);
        self::$ins = $_self;
        return $_self;
    }
    private function __construct($config){
        $this->config = $config;
    }

    /**
     * 上传文件
     * @param $fileObj      $_file['name'] 对象
     * @param $savePath     `保存路径
     * @param bool $isRandName  `是否更改为随机名称
     * @return array|bool
     */
    public function one($fileObj,$savePath,$isRandName = true){
        if (empty($fileObj)){
            return ['msg'=>'请选择文件','code'=>-1];
        }
        $this->size = $fileObj['size'];
        if ( false === $this->isSize() ){
            return ['msg'=>'文件不能超过'.$this->config['maxSize'].'/KB','code'=>-1];
        }
        $this->type = \Ne::$app->get->fileHZ($fileObj['name']);
        if ( false === $this->isType() ){
            return ['msg'=>'不允许上传'.$this->type.'格式的文件','code'=>-1];
        }
        if ( true === $isRandName){
            $name = md5(\Ne::$app->get->rand(18).'/'.time());
        }else{
            $name = \Ne::$app->fileOperation->getFilename($fileObj['name']);
        }
        if (!is_dir($savePath)){
            mkdir($savePath,0777,true);
        }
        return $this->move($savePath.'/'.$name.'.'.$this->type,$fileObj);
    }

    /**
     * 上传多个文件
     * @param $savePath     `支持数组、字符串
     * @param $isRandName   `是否随机命名，支持数组、bool
     * @return array
     */
    public function files($savePath , $isRandName ){
        $array = [];
        $i = 1;
        foreach ($_FILES as $key => $value){
            if (is_array($savePath) && isset($savePath[$key])){
                $path = $savePath[$key];
            }else{
                $path = $savePath;
            }
            if (is_array($isRandName) && isset($isRandName[$key]) && true === $isRandName[$key] ){
                $res = $this->one($_FILES[$key],$path,true);
            }else{
                $res = $this->one($_FILES[$key],$path,false);
            }
            if ( true === $res){
                $array[$i - 1] = ['msg'=>'第'.$i.'个文件上传成功',200];
            }else{
                $array[$i - 1] = ['msg'=>'第'.$i.'个文件上传失败',-1];
            }
            $i++;
        }
        return $array;
    }
    /**
     * 移动文件
     * @param $save
     * @param $obj
     * @return bool
     */
    private function move($save,$obj){
        if ( @move_uploaded_file($obj['tmp_name'], $save) ){
            return true;
        }
        return false;
    }
    /**
     * 判断格式
     * @return bool
     */
    private function isType(){
        $allowType = $this->config['allowType'];
        if (is_array($allowType)){
            if (in_array($this->type,$allowType)){
                return true;
            }else{
                return false;
            }
        }
        $array = explode(',',$allowType);
        if (in_array($this->type,$array)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 判断大小
     * @return bool
     */
    private function isSize(){
        $maxSize = $this->config['maxSize'];
        if ( floor($this->size / 1024) > $maxSize){
            return false;
        }
        return true;
    }

}
