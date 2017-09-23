<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NComponent\File;
/**
 *  mode 打开文件模式 说明
 *  r	打开文件为只读。文件指针在文件的开头开始。
 *  w	打开文件为只写。删除文件的内容或创建一个新的文件，如果它不存在。文件指针在文件的开头开始。
 *  a	打开文件为只写。文件中的现有数据会被保留。文件指针在文件结尾开始。创建新的文件，如果文件不存在。
 *  x	创建新文件为只写。返回 FALSE 和错误，如果文件已存在。
 *  r+	打开文件为读/写、文件指针在文件开头开始。
 *  w+	打开文件为读/写。删除文件内容或创建新文件，如果它不存在。文件指针在文件开头开始。
 *  a+	打开文件为读/写。文件中已有的数据会被保留。文件指针在文件结尾开始。创建新文件，如果它不存在。
 *  x+	创建新文件为读/写。返回 FALSE 和错误，如果文件已存在。
 */
/**
 * Class Operation
 * @package NComponent\File
 */
class Operation {
    function getDirAndFile ( $dir )
    {
        $result = array();
        $handle = opendir($dir);
        if ( $handle ) {
            while ( ( $file = readdir ( $handle ) ) !== false ) {
                if ( $file != '.' && $file != '..') {
                    $cur_path = $dir . DIRECTORY_SEPARATOR . $file;
                    if ( is_dir ( $cur_path ) ) {
                        $name = str_replace(ROOT_PATH,'',str_replace('//','/',$cur_path));
                        $result['dir'][$name] = $this->getDirAndFile( $cur_path );
                    } else {
                        $result['file'][] = str_replace(ROOT_PATH,'',str_replace('//','/',$cur_path));
                    }
                }
            }
            closedir($handle);
        }
        return $result;
    }
    public function getDir($path){
        $result = array();
        $handle = opendir($path);
        if ( $handle ) {
            while ( ( $file = readdir ( $handle ) ) !== false ) {
                if ( $file != '.' && $file != '..') {
                    $cur_path = $path .'/' .$file;
                    if ( !is_file( $cur_path ) ) {
                        $result[$file] = [
                            'name'=> $file,
                            'path'=> str_replace(ROOT_PATH,'',str_replace('//','/',$cur_path))
                        ];
                    }
                }
            }
            closedir($handle);
        }
        return $result;
    }
    public function getFile($path) {
        $result = array();
        $handle = opendir($path);
        if ( $handle ) {
            while ( ( $file = readdir ( $handle ) ) !== false ) {
                if ( $file != '.' && $file != '..') {
                    $cur_path = $path .'/' .$file;
                    if ( is_file( $cur_path ) ) {
                        $result[] = str_replace(ROOT_PATH,'',$file);
                    }
                }
            }
            closedir($handle);
        }
        return $result;
    }
    /**
     * 获取目录的磁盘总大小
     * @param $disk
     * @return bool|float
     */
    public function getDiskTotalSpace($disk){
        if (!is_dir($disk)){
            return false;
        }
        return disk_total_space($disk);
    }
    /**
     * 获取目录中的可用空间
     * @param $disk
     * @return bool|float
     */
    public function getDiskFreeSpace($disk){
        if (!is_dir($disk)){
            return false;
        }
        return disk_free_space($disk);
    }
    /**
     * 复制文件
     * @param $file         `源文件
     * @param $newFile      `新的文件路径
     * @param bool $cover   `如果新的文件已经存在是否覆盖
     * @return bool
     */
    public function copy($file,$newFile,$cover = false){
        if (!file_exists($file)){
            return false;
        }
        if (false === $cover){
            if ( file_exists($newFile) ){
                return false;
            }
        }
        return copy($file, $newFile);
    }
    /**
     * 获取文件名称
     * @param $file
     * @return string
     */
    public function getFilename($file){
        $type = pathinfo($file,PATHINFO_EXTENSION);
        return basename($file,$type);
    }

    /**
     * 写入数据到文件
     * @param $file         `文件路径
     * @param $content      `内容
     * @param string $mode  `写入模式 一般为
     * @return bool
     */
    public function writeFile($file , $content ,$mode = 'a'){
        if (!file_exists($file) || '' === $content){
            return false;
        }
        $fOpen = fopen($file,strtolower($mode));
        if ( false === $fOpen){
            return false;
        }
        fwrite($fOpen,$content);
        fclose($fOpen);
        return true;
    }
    /**
     * 删除目录及目录下所有文件或删除指定文件
     * @param string $path   待删除目录路径
     * @param int|bool $delDir 是否删除目录，1或true删除目录，0或false则只删除文件保留目录（包含子目录）
     * @return bool 返回删除状态
     */
    public function delDirOrFile($path, $delDir = true) {
        $handle = opendir($path);
        if ($handle) {
            while (false !== ( $item = readdir($handle) )) {
                if ($item != "." && $item != "..")
                    is_dir("$path/$item") ? $this->delDirOrFile("$path/$item", $delDir) : @unlink("$path/$item");
            }
            closedir($handle);
            if ($delDir)
                return rmdir($path);
        }else {
            if (file_exists($path)) {
                return @unlink($path);
            } else {
                return false;
            }
        }
    }
}