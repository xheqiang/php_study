<?php

/**
 * Created by PhpStorm.
 * User: qiang
 * Date: 17/1/17
 * Time: 下午3:47
 */
class DB
{
    const DB_MOTHOD_CREATE = "create";    //新增
    const DB_MOTHOD_SAVE = "save";    //编辑
    const DB_MOTHOD_FINDFIRST = "findFirst";    //查询单条
    const DB_MOTHOD_FIND = "find";    //查询所有
    const DB_MOTHOD_DELETE = "delete";    //删除所有
    const DB_MOTHOD_COUNT = "count";    //统计条数

    protected $_dbHandle;    //数据库连接句柄
    protected $_table = "";  //表名称
    private $_cloumn = "";  //查询的数据库列
    private $_where = "";   //拼接where 条件
    private $_sql = "";     //拼接的sql语句
    private $_order = "";   //拼接的排序语句
    private $_data = "";    //编辑&&新建传入的data

    public function __construct($db_config)
    {
        $this->connect($db_config['db_type'], $db_config['db_host'], $db_config['db_port'], $db_config['db_user'], $db_config['db_pass'], $db_config['db_name']);
    }

    /**
     * 连接数据库
     * @param $db_type
     * @param $db_host
     * @param $db_port
     * @param $db_user
     * @param $db_pass
     * @param $db_name
     */
    public function connect($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name)
    {
        try {
            $dsn = sprintf("%s:host=%s;port=%s;dbname=%s;charset=utf8", $db_type, $db_host, $db_port, $db_name);
            $db_option = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
            $this->_dbHandle = new PDO($dsn, $db_user, $db_pass, $db_option);
        } catch (PDOException $e) {
            exit('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * 创建数据
     * 可以使用data() 传递数据
     * 返回受影响条数
     * @param null $data
     * @return mixed
     */
    public function create($data = null)
    {
        $this->data($data);
        $this->buildsql(self::DB_MOTHOD_CREATE);
        $dispatch = $this->execute();
        return $dispatch->rowCount();
    }

    /**
     * 修改数据
     * 根据条件，可以指定 where() order() limit()
     * 可以使用data() 传递数据
     * 返回受影响条数
     * @param null $data
     * @return mixed
     */
    public function save($data = null)
    {
        $this->data($data);
        $this->buildsql(self::DB_MOTHOD_SAVE);
        $dispatch = $this->execute();
        return $dispatch->rowCount();
    }

    /**
     * 查询单条
     * 根据条件，可以指定 where() order() limit()
     * 可以执行查询字段 cloumn
     * 返回查询到的数据数组
     * @return mixed
     */
    public function findFirst()
    {
        $this->buildsql(self::DB_MOTHOD_FINDFIRST);
        $dispatch = $this->execute();
        return $dispatch->fetch();
    }

    /**
     * 查询所有
     * 根据条件，可以指定 where() order() limit()
     * 可以执行查询字段 cloumn
     * 返回查询到的数据数组
     * @return mixed
     */
    public function find()
    {
        $this->buildsql(self::DB_MOTHOD_FIND);
        $dispatch = $this->execute();
        return $dispatch->fetchAll();
    }

    /**
     * 删除数据
     * 根据条件删除，可以指定  where() order() limit()
     * @return mixed
     */
    public function delete()
    {
        $this->buildsql(self::DB_MOTHOD_DELETE);
        $dispatch = $this->execute();
        return $dispatch->rowCount();
    }

    public function count()
    {
        $this->buildsql(self::DB_MOTHOD_COUNT);
        $dispatch = $this->execute();
        $result = $dispatch->fetch();
        return $result['count'];
    }

    /**
     * 设置表明
     * @param $table_name
     * @return $this
     */
    public function table($table_name)
    {
        $this->_table = $table_name;
        return $this;
    }

    /**
     * save() create() 连贯操作可以调用data()方法
     * @param null $data
     * @return $this
     */
    public function data($data = null)
    {
        $this->_data = !empty($data) ? $data : $this->_data;  //data值以save($data)中传递的为主，save中传递的会覆盖data($data)中的值
        return $this;
    }

    /**
     * 指定查询字段
     * findFrist()&&find() 可以使用
     * @param null $cloumn
     * @return $this
     */
    public function cloumn($cloumn = null)
    {
        $this->_cloumn = !empty($cloumn) ? " " : " * ";
        if (is_array($cloumn)) {
            $cloumnArr = array();
            foreach ($cloumn as $key => $value) {
                $cloumnArr[] = $value;
            }
            $this->_cloumn .= implode(',', $cloumnArr);
        } else {
            $this->_cloumn .= $cloumn;
        }
        return $this;
    }

    /**
     * 拼接where 条件
     * save()&&findFrist()&&find()&&delete()方法可以使用
     * @param array $where
     * @return $this
     */
    public function where($where = null)
    {
        $this->_where = !empty($where) ? " " : " 1=1 ";
        if (is_array($where)) {
            $whereArr = array();
            foreach ($where as $key => $value) {
                $whereArr[] = sprintf("`%s` = '%s'", $key, $value);
            }
            $this->_where .= implode(' and ', $whereArr);
        } else {
            $this->_where .= $where;
        }
        return $this;
    }

    /**
     * 排序方法
     * save() && findFrist() && find() && delete()方法可以使用
     * @param null $order
     * @return $this
     */
    public function order($order = null)
    {
        if (is_array($order)) {
            $orderArr = array();
            foreach ($order as $key => $value) {
                $orderArr[] = sprintf("`%s` %s", $key, $value);
            }
            $this->_order .= implode(',', $orderArr);
        } else {
            $this->_order .= $order;
        }
        return $this;
    }

    /**
     * 限制条件
     * save()&&findFrist()&&find()&&delete()方法可以使用
     * @return $this
     */
    public function limit($limit = null)
    {
        if (is_array($limit)) {
            $start = !empty($limit["start"]) ? $limit["start"] . "," : "";
            $end = $limit["end"];
            $this->_limit = !empty($end) ? $start . $end : "";
        } else {
            $this->_limit = $limit;
        }
        return $this;
    }

    /**
     * 执行sql语句方法
     * CURD方法调用该方法 外部也可以直接调用该方法，用于指向原生sql语句
     * @return mixed
     */
    public function execute($sql = null)
    {
        $this->_sql = !empty($sql) ? $sql : $this->_sql;    //exexute 中的sql会覆盖生成的sql,该方法便于执行原生的sql方法
        $dispatch = $this->_dbHandle->prepare($this->_sql);
        $dispatch->execute();

        return $dispatch;
    }

    /**
     * 打印最后执行的sql语句，便于调试
     * @return string
     */
    public function getLastSql()
    {
        return $this->_sql;
    }

    /**
     * CURD方法调用的公用方法，用于拼接sql语句
     * @param null $method
     */
    private function buildSql($method = null)
    {
        $cloumnStr = !empty($this->_cloumn) ? $this->_cloumn : " * ";
        $whereStr = !empty($this->_where) ? $this->_where : " 1=1 ";
        $orderStr = !empty($this->_order) ? "order by " . $this->_order : "";
        $limitStr = !empty($this->_limit) ? "limit " . $this->_limit : "";

        switch ($method) {
            case self::DB_MOTHOD_CREATE:
                $createDataSql = $this->formatCreateData();
                $this->_sql = sprintf("insert into `%s` %s ", $this->_table, $createDataSql);
                break;
            case self::DB_MOTHOD_SAVE:
                $saveDataSql = $this->formatSaveData();
                $this->_sql = sprintf("update `%s` set %s where %s %s %s", $this->_table, $saveDataSql, $whereStr, $orderStr, $limitStr);
                break;
            case self::DB_MOTHOD_FINDFIRST:
                $this->_sql = sprintf("select %s from `%s` where %s %s", $cloumnStr, $this->_table, $whereStr, $orderStr);
                break;
            case self::DB_MOTHOD_FIND:
                $this->_sql = sprintf("select %s from `%s` where %s %s %s", $cloumnStr, $this->_table, $whereStr, $orderStr, $limitStr);
                break;
            case self::DB_MOTHOD_DELETE:
                $this->_sql = sprintf("delete from `%s` where %s %s %s", $this->_table, $whereStr, $orderStr, $limitStr);
                break;
            case self::DB_MOTHOD_COUNT:
                $this->_sql = sprintf("select count(*) as count from `%s` where %s %s %s", $this->_table, $whereStr, $orderStr, $limitStr);
                break;
            default:
                echo "Warning: Illegal operation";
                break;
        }

    }

    /**
     * 格式化新建数据的sql语句
     * @return string
     */
    private function formatCreateData()
    {
        $data = (array)$this->_data;
        $fields = array();
        $values = array();
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s`", $key);
            $values[] = sprintf("'%s'", $value);
        }

        $field = implode(',', $fields);
        $value = implode(',', $values);

        return sprintf("(%s) values (%s)", $field, $value);
    }

    /**
     * 格式化新建数据的sql语句
     * @return string
     */
    private function formatSaveData()
    {
        $data = (array)$this->_data;
        $fields = array();
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s` = '%s'", $key, $value);
        }

        return implode(',', $fields);
    }

}