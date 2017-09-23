<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Db
 */
namespace NComponent\Db;

interface Db {
    public static function ins();
    public function query($sql,$data = []);
    public function exec($sql,$data = []);
    public function table($table);
    public function fetch();
    public function where( $data, $orAndArr = []);

    public function order($data,$sort = 'DESC');
    public function group($data,$sort = 'DESC');
    public function limit($page,$pageSize = 10);
    public function number( $number);
    public function begin();
    public function commit();
    public function rollback();

//    abstract protected function query($sql,$data = []);
//    abstract protected function exec($sql,$data = []);
//    abstract protected function table($table);
//    abstract protected function fetch();
//    abstract protected function where( ? array  $data = [] , ? array  $orAndArr = []);
//
//    abstract protected function begin();
//    abstract protected function commit();
//    abstract protected function rollback();
}
