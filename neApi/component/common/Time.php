<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NComponent\Common;

class Time {

    /**
     * 时间差
     * @param bool $day1  默认 获取实时时间
     * @param $day2
     * @return bool|string
     */
    public function timeDifference($day1 = true, $day2){
        $second1 = $day1 == true ? time() : strtotime($day1);
        $second2 = strtotime($day2);
        if (ceil($second1 - $second2) < 0)
            return false;
        //返回字符串  格式 天:时:分:秒
        $str = ceil(($second1 - $second2) / (3600 * 24)).':'.ceil(($second1 - $second2)/3600).':'.ceil(($second1 - $second2) /60).':'.ceil($second1 - $second2);
        return explode(':',$str);
    }

    /**
     * 格式化时间
     * @param $time
     * @param int $type
     * @return string
     */
    public function formatTime($time , $type = 1){
        if ( false === \Ne::$app->check->time($time)){
            return $time;
        }
        if ( 1 ===  $type ){
            // 根据 时间 返回 格式为 年-月份-天数
            $arr = explode(' ',$time);
            $arr1 = explode('-',$arr[0]);
            $arr2 = explode(':',$arr[1]);
            return $arr1[0].'-'.$arr1[1].'-'.$arr1[2];
        } else if ( 2 === $type){
            // 根据 时间 返回 格式为 月份-天数 时:分
            $arr = explode(' ',$time);
            $arr1 = explode('-',$arr[0]);
            $arr2 = explode(':',$arr[1]);
            return $arr1[1].'-'.$arr1[2].' '.$arr2[0].':'.$arr2[1];
        }else{
            return $time;
        }

    }
    /**
     * 时间前
     * @param $the_time
     * @return string
     */
    public function beforeTime($the_time){
        $now_time = time();
        $show_time = strtotime($the_time);
        $dur = $now_time - $show_time;
        if($dur < 60){
            return $dur.'秒前';
        }else if($dur < 3600){
            return floor($dur/60).'分钟前';
        }else if($dur < 86400) {
            return floor($dur/3600).'小时前';
        }else if($dur < 259200) {//3天内
            return floor($dur / 86400) . '天前';
        }else{
            return $the_time;
        }
    }
    /**
     * 获取时间
     * @return false|string
     */
    public function time(){
        return date('Y-m-d H:i:s',time());
    }

}