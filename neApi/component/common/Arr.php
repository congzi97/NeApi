<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\common
 */
namespace NComponent\Common;
/**
 * Class Arr
 * @package NComponent\Common
 */
class Arr {
    /**
     * 数组转字符串
     * @param array|null $data
     * @return string
     */
    public function toParams( ? array  $data){
        $toStr = '';
        foreach ($data as $key => $value){
            if (is_array($data[$key])){
                self::toParams($data[$key]);
            }else{
                $toStr = '' === $toStr ? $key.'='.$value : '&'.$key.'='.$value;
                $toStr .= $toStr;
            }
        }
        return $toStr;
    }
    /**
     * 字符串转数组
     * @param $data
     * @return array
     */
    public function toArray( $data){
        $toArr = [];
        $arr = explode('&',$data);
        $count = count($arr);
        for ($i = 0; $i < $count ; $i++){
            $tmp = explode('=',$arr[$i]);
            $toArr[$tmp[0]] = $tmp[1];
        }
        return $toArr;
    }
    /**
     * 二维数组 根据某个值排序
     * @param $array
     * @param $field
     * @param string $direction
     * @return array
     */
    public function TDSort($array,$field,$direction = 'SORT_DESC'){
        if (!is_array($array)){
            return $array;
        }
        if ($direction != 'SORT_DESC'){
            if ($direction != 'SORT_ASC'){
                return $array;
            }
        }
        $arrSort = array();
        foreach($array AS $id => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$id] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($direction), $array);
        return $array;
    }
    /**
     * 直接删除数组方法
     * @param $data
     * @param $key
     * @return mixed
     */
    public function remove($data, $key){
        if(!array_key_exists($key, $data)){
            return $data;
        }
        $keys = array_keys($data);
        $index = array_search($key, $keys);
        if($index !== FALSE){
            unset($data[$index]);
            array_splice($data, $index, 1);
        }
        return $data;
    }

    /**
     * 重装数组方法
     * @param $data
     * @param $delKey
     * @return array
     */
    public static  function removeAll($data,$delKey){
        $newArray = array();
        if(is_array($data)) {
            foreach($data as $key => $value) {
                if($key !== $delKey) {
                    $newArray[$key] = $value;
                }
            }
        }else {
            $newArray = $data;
        }
        return $newArray;
    }
}
