<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Db
 */
namespace NComponent\Db;
use NeApi\NeApiError;
class MySql implements Db {
    /**
     * 连接数据库对象
     * @var string | Object
     */
    private $conn = '';
    // 数据库表
    private $table      =   '';
    // 是否开启事物
    private $begin      =   false;
    // 条件
    private $whereStr   =   '';
    // 真实数据
    private $whereArr   =   [];
    // limit
    private $limit      =   '';
    // order
    private $order      =   '';
    // group
    private $group      =   '';
    // 查询的字段
    private $queryField =   ' * ';
    private $config     = [];
    // 判断是否已经new
    static private $ins         = null;
    public static function ins(){
        if (self::$ins instanceof self){
            return self::$ins;
        }
        self::$ins = new self();
        return self::$ins;
    }
    private function __construct(){
        $this->config = include ROOT_PATH.'/config/db.php';
        $this->init();
    }
    public function __clone(){
        // TODO: Implement __clone() method.
    }
    private function init(){
        if ( '' !== $this->conn){
            return $this->conn;
        }
        // 连接数据库
        try {
            $this->conn = new \PDO($this->config['dsn'],$this->config['username'],$this->config['password']);
            if (!$this->conn){
                throw new \PDOException('连接数据库失败');
            }
            $this->conn->exec('USE  '.$this->config['database']);
            $this->conn->exec('SET NAMES '.$this->config['charset']);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->setAttribute(\PDO::ATTR_AUTOCOMMIT, false);
            $this->conn->setAttribute(\PDO::ATTR_PERSISTENT,true);
            return $this->conn;
        }catch (\PDOException $exception){
            NeApiError::error($exception);
        }
    }
    /**
     * 查询SQL语句 -> 获取数据集
     * @param $sql              SQL语句
     * @param array $data       真实数据
     * @param bool $fetchAll    是否获取所以 默认 false
     * @return array|mixed
     */
    public function query($sql,$data = [],$fetchAll = false){
        try {
            $conn = $this->init();
            $sql = str_replace('{prefix}',$this->config['prefix'],$sql);
            $stm  = $conn->prepare($sql,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $stm->execute($data);
            unset($data);
            unset($this->whereArr);
            return $fetchAll == true ? $stm->fetchAll(\PDO::FETCH_ASSOC) : $stm->fetch(\PDO::FETCH_ASSOC);
        }catch (\PDOException $exception){
            NeApiError::error($exception);
        }
    }

    /**
     * 查询SQL语句 -> 影响行数
     * @param $sql
     * @param array $data
     * @param bool $return_id  插入数据时有效->是否返回新的ID
     * @return int
     */
    public function exec($sql,$data = [] , $return_id = false){
        try {
            $conn = $this->init();
            $sql = str_replace('{prefix}',$this->config['prefix'],$sql);
            $stm  = $conn->prepare($sql,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $stm->execute($data);
            unset($data);
            unset($this->whereArr);
            return $stm->rowCount();
        }catch (\PDOException $exception){
            NeApiError::error($exception);
        }

    }

    /**
     * 初始化数据库表
     * @param $table
     * @return $this
     */
    public function table($table){
        $this->table = $this->config['prefix'].$table;
        try {
            if ( false === $this->isTable($this->table)){
                throw new \PDOException('NOT FOUND  TABLE '.$this->table);
            }
            return $this;
        }catch (\PDOException $exception){
            NeApiError::error($exception);
        }
    }
    /**
     * 查询一条记录
     * @return array
     */
    public function fetch(){
        if (empty($this->table)){
            return [];
        }
        if ( empty($this->whereStr) ){
            $sql  = ' SELECT '.$this->queryField.' FROM '.$this->addChar($this->table) .' '.$this->order .' '.$this->group;
        }else{
            $sql  = ' SELECT '.$this->queryField.' FROM '.$this->addChar($this->table) .' WHERE ' .' '.$this->whereStr.' '.$this->order .' '.$this->group;
        }
        $all = $this->query($sql,$this->whereArr);
        return $all;
    }
    /**
     * 查询多条记录
     * @return array
     */
    public function fetchAll(){
        if (empty($this->table)){
            return [];
        }
        if ( empty($this->whereStr) ){
            $sql  = ' SELECT '.$this->queryField.' FROM '.$this->addChar($this->table) .' '.$this->order .' '.$this->group .' '.$this->limit;
        }else{
            $sql  = ' SELECT '.$this->queryField.' FROM '.$this->addChar($this->table) .' WHERE ' .' '.$this->whereStr.' '.$this->order .' '.$this->group .' '.$this->limit;
        }
        $all = $this->query($sql,$this->whereArr,true);
        return $all;
    }

    /**
     * 查询行数
     * @return int
     */
    public function row(){
        if (empty($this->table)){
            return 0;
        }
        if ( empty($this->whereStr) ){
            $sql  = ' SELECT '.$this->queryField.' FROM '.$this->addChar($this->table) .' '.$this->order .' '.$this->group .' '.$this->limit;
        }else{
            $sql  = ' SELECT '.$this->queryField.' FROM '.$this->addChar($this->table) .' WHERE ' .' '.$this->whereStr.' '.$this->order .' '.$this->group .' '.$this->limit;
        }
        $row = $this->exec($sql,$this->whereArr);
        return $row;
    }

    /**
     * 更新
     * @param $data
     * @return int
     */
    public function update($data){
        if (empty($this->table)){
            return 0;
        }
        if ( empty($this->whereStr) ){
            return 0;
        }
        $field = '';
        $valueArr = [];
        $xxs   = \Ne::$app->xxs;
        foreach ($data as $key => $value){
            $value = $xxs->string_remove_xss($value);
            $field .= '' === $field ? $key.'=:'.$key : ','.$key.'=:'.$key;
            $valueArr[':'.$key] = $value;
        }
        $sql  = ' UPDATE '.$this->addChar($this->table).' SET ' . $field .' WHERE ' .' '.$this->whereStr.' '.$this->order .' '.$this->group .' ' .$this->limit ;
        $newArray = array_merge($valueArr,$this->whereArr);
        unset($data);
        unset($valueAr);
        unset($this->whereArr);
        return $this->exec($sql,$newArray);
    }

    /**
     * 删除
     * @return int
     */
    public function delete(){
        if (empty($this->table)){
            return 0;
        }
        if ( empty($this->whereStr) ){
            return 0;
        }
        $sql  = ' DELETE FROM  '.$this->addChar($this->table).' WHERE ' .' '.$this->whereStr.' '.$this->order .' '.$this->group .' ' .$this->limit ;

        return $this->exec($sql,$this->whereArr);
    }

    /**
     * 插入数据
     * @param $data
     * @param bool $return_id
     * @param string $table
     * @return int
     */
    public function insert($data, $return_id = false , $table = '' ){
        if ( '' === $table){
            if (empty($this->table)){
                return 0;
            }
        }else{
            $this->table($table);
        }
        $fieldArr = [];
        $valueArr = [];
        $xxs   = \Ne::$app->xxs;
        foreach ($data as $key => $value){
            $value              = $xxs->string_remove_xss($value);
            $fieldArr[$key]     = ':'.$key;
            $valueArr[':'.$key] = $value;
        }
        $sql = 'INSERT INTO '.$this->addChar($this->table).' ('.implode(',',array_keys($fieldArr)).') VALUES ('.implode(',',array_values($fieldArr)).') ';
        unset($data);
        return $this->exec($sql,$valueArr , $return_id);
    }
    /**
     * 查询字段
     * @param array|null $array
     * @return $this
     */
    public function queryField($array = []){
        $this->queryField = implode(',',$array);
        return $this;
    }
    /**
     * 分页获取数据
     * @param $page
     * @param int $pageSize
     * @return $this
     */
    public function limit($page,$pageSize = 10){
        $page           = is_numeric($page) && $page > 0 ?  $page : 1 ;
        $pageSize       = intval($pageSize);
        $xpage          = intval(($page - 1) * $pageSize);
        $this->limit    = " limit {$xpage},{$pageSize}";
        return $this;
    }
    /**
     * 更新、删除时候的个数
     * @param int|null $number
     * @return $this
     */
    public function number($number){
        $this->limit = 'limit  '.$number;
        return $this;
    }
    /**
     * 添加 order
     * @param $data
     * @return $this
     */
    public function order($data,$sort = 'DESC'){
        if (is_string($data)){
            $this->order = ' ORDER BY '. $this->addChar($data) .' ' . $sort;
            return $this;
        }
        $this->order = ' ORDER BY '. implode(',',$data) .'  '.$sort;
        return $this;
    }
    /**
     * 添加 group
     * @param $data
     * @return $this
     */
    public function group($data,$sort = 'DESC'){
        if (is_string($data)){
            $this->group = ' GROUP BY '. $this->addChar($data) .' ' . $sort;
            return $this;
        }
        $this->group = ' GROUP BY '. implode(',',$data) .'  '.$sort;
        return $this;
    }
    /**
     * 为SQL语句添加条件
     * @param array $data
     * @param array|null $orAndArr
     * @return $this
     */
    public function where( $data ,  $orAndArr = []){
        # $key => $value || $key => [$value,params] params 默认为  =
        # 1 key=:key 2 :key = value
        if ( !is_array($data) ){
            return $this;
        }
        $whereStr   =   '';
        $whereArr   =   [];
        $i          =   0;
        $xxs   = \Ne::$app->xxs;
        foreach ($data as $key => $value){
            if ( 0 < $i){
                $orAnd = isset($orAndArr[$i - 1]) ? $orAndArr[$i - 1] : ' And ';
            }else{
                $orAnd = '';
            }
            if (is_array($value)){
                $value = $xxs->string_remove_xss($value[0]);
                $operation = isset($value[1]) ? $this->getOperation($value[1]) : '=';
                $whereStr .= '  ' .$orAnd . '  ' .$this->addChar($key) . $operation . ':'.$key;
                $whereArr[':'.$key]     =   $value[0];
            }else{
                $value = $xxs->string_remove_xss($value);
                $whereStr .= '  ' .$orAnd . '  ' .$this->addChar($key) . '=:'.$key;
                $whereArr[':'.$key]     =   $value;
            }
            $i++;
        }
        unset($data);
        $this->whereStr = $whereStr;
        $this->whereArr = $whereArr;
        return $this;
    }
    /**
     * 获取条件
     * @param $value
     * @return string
     */
    private function getOperation($value){
        switch ($value){
            case '=':
                return '=';
            case '!=':
                return '!=';
            case '<>':
                return '<>';
            case '>':
                return '>';
            case '<':
                return '<';
            default:
                return '=';
        }
    }
    /**
     * 开启事物
     * @return $this
     */
    public function begin(){
        if ( false === $this->begin) {
            $this->begin = true;
            $conn = $this->init();
            $conn->beginTransaction();
            return $this;
        }
        return $this;
    }

    /**
     * 执行事物
     * @return $this
     */
    public function commit() {
        if ( true === $this->begin) {
            $this->begin = false;
            $conn = $this->init();
            $conn->commit();
            return $this;
        }
    }

    /**
     * 滚回事物
     * @return $this
     */
    public function rollback() {
        if ( true === $this->begin) {
            $this->begin = false;
            $conn = $this->init();
            $conn->rollBack();
            return $this;
        }
    }
    /**
     * 判断表是否存在
     * @param $table
     * @return bool
     */
    private function isTable( $table){
        $conn = $this->init();
        $sql = "SHOW TABLES FROM " . $this->config['database'];
        $prepare = $conn->prepare($sql);
        $prepare->execute();
        $all = $prepare->fetchAll(\PDO::FETCH_NUM);
        for ($i = 0; $i < count($all);$i++){
            if ($table == $all[$i][0]){
                return true;
            }
        }

        return false;
    }
    /**
     * 判断字段是否存在
     * @param $table
     * @param null|string $field
     * @return bool
     */
    private function isField($field,$table = null){
        $conn = $this->init();
        $table = $this->table;
        $sql = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME=\''.trim($table,'`').'\' AND TABLE_SCHEMA=\''.$this->config['database'].'\'';
        $prepare = $conn->prepare($sql);
        $prepare->execute();
        $all = $prepare->fetchAll(\PDO::FETCH_ASSOC);
        $count = count($all);
        for ($i = 0; $i < $count ; $i++){
            if ($all[$i]['COLUMN_NAME'] == $field){
                return true;
            }
        }

        return false;
    }
    /**
     * 添加``
     * @param $str
     * @return string
     */
    private function addChar($str){
        return '`'.trim($str).'`';
    }

}

