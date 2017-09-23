<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NeApi;

class Log {

    private static $filename = '';
    private static $dir = '';

    /**
     * 检查文件是否存在、大小是否过大，返回新的昵称
     * @return string
     */
    private static function checkFile(){
        if (file_exists(self::$dir.self::$filename)){
            $fileSize = filesize(self::$dir.self::$filename);
            if (ceil($fileSize/1024) > 1024){
                $tmpArr = explode('.',self::$filename);
                $fileName = $tmpArr[0];
                $fileType = $tmpArr[1];
                $i = 1;
                while (true) {
                    $i ++;
                    // 寻找新的文件名
                    if (file_exists(self::$dir.$fileName.'_'.$i.'.'.$fileType)){
                        $fileSize = filesize(self::$dir.$fileName.'_'.$i.'.'.$fileType);
                        if (ceil($fileSize/1024) > 1024){
                            continue;
                        }else{
                            $open = fopen(self::$dir.$fileName.'_'.$i.'.'.$fileType,'w');
                            fclose($open);
                            return self::$dir.$fileName.'_'.$i.'.'.$fileType;
                        }
                    }else{
                        return self::$dir.$fileName.'_'.$i.'.'.$fileType;
                    }
                }
            }else{
                return self::$dir.self::$filename;
            }
        }else{
            $open = fopen(self::$dir.self::$filename,'w');
            fclose($open);
            return self::$dir.self::$filename;
        }
    }
    /**
     * 记录日志
     * @param $content
     * @param null $file_name
     * @param null $d_dir
     * @return bool
     */
    public function log($content,$file_name = null,$d_dir = null){
        if ('' === $content){
            return false;
        }
        if (null == $d_dir){
            $dir = ROOT_PATH.'/var/log/'.date('Ymd').'/';
        }else{
            $dir = ROOT_PATH.'/var/log/'.date('Ymd').'/'.$d_dir.'/';
        }
        if (!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        self::$dir = $dir;
        self::$filename = null == $file_name ? date('Ymd').'.txt' : $file_name.'.txt' ;
        $pathFile  =  self::checkFile();
        $fOpen = fopen($pathFile,'a');
        fwrite($fOpen,$content);
        fclose($fOpen);
        return true;
    }

}