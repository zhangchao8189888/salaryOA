<?php
class db{
    function db(){
    }
/**
 * 数据库连接
 * @var unknown_type
 */
var $mysql_db_connect;
/**
 * 获取数据库连接
 */
function get_db_connect(){
   if(!$this->mysql_db_connect){
   $this->mysql_db_connect=mysql_connect(DB_HOST,DB_USER,DB_PASS,false);
   mysql_query('set names utf8',$this->mysql_db_connect);
		if(mysql_select_db(DB_NAME,$this->mysql_db_connect)){
			return $this->mysql_db_connect;
		}else {
			return false;
		}
	}else {
		return $this->mysql_db_connect;
	}
}
/**
 *关闭数据库
 */
function closeConnect()
    {
        $dbClose = is_resource($this->mysql_db_connect);
        if ($dbClose) {
            $dbClose = mysql_close($this->mysql_db_connect);
        }
        return $dbClose;
    }
/**
 * 执行sql语句
 * @param $query
 * @param $db
 */
function g_db_query($query,$db=null){
    $db = $this->get_db_connect();
   if(!$db){
    return false;
    }
   //echo $query."</br>";
    //输出sql
    $result=mysql_query($query,$db);
    if(!$result){
    $LOG = new log();
 	$LOG->setLogdata( 'sql_msg', $query);
 	$LOG->write('sql_msg');
    }
	return $result;
}
   /**
     * 事务开始
     * 
     * @return $dbTransaction
     */
    function beginTransaction(){
        $this->g_db_query("SET AUTOCOMMIT=0");
        $dbTransaction =$this->g_db_query("START TRANSACTION");
        return $dbTransaction;
    }

    /**
     * 事务提交
     * 
     * @return $dbCommit
     */
    function commit(){
        $dbCommit = $this->g_db_query("COMMIT");
        $this->g_db_query("SET AUTOCOMMIT=1");
        return $dbCommit;
    }

    /**
     * 事务回滚
     * 
     * @return $dbRollback
     */
    function rollback(){
        $dbRollback = $this->g_db_query("ROLLBACK");
        return $dbRollback;
    }
/**
 * 插入一条数据
 *
 * @param string $table
 * @param array $vars
 * @param resource $db
 * @return int 影响的行数 或是 错误代码
 */
function g_db_insert($table,$vars,$db=null){
	if (!$table||!is_array($vars)) {
		return false;
	}
	$keystr = '';
	$valuestr = '';
	foreach ($vars as $key => $value) {
		if (empty($key)||is_numeric($key)) {
			continue;
		}
		$comma = '';
		if (!empty($keystr)) {
			$comma = ',';
		}
		$keystr .= $comma.$key;
		$valuestr .= $comma."'".mysql_escape_string($value)."'";
	}
	if (!empty($keystr)) {
		$query = 'insert ignore into '.$table.'('.$keystr.') values('.$valuestr.')';
		$result = $this->g_db_query($query,$db);
		if ($result) {
			return true;
		}else {
			return false;
		}
	}
	return false;
}
/**
 * 插入多条记录
 *
 * @param string $table
 * @param array $muti_vars
 * @param resource $db
 * @return int 影响的行数 或是 错误代码
 */
function g_db_muti_insert($table,$muti_vars,$db=null){
	if (!$table||!is_array($vars)) {
		return false;
	}
	$valuearr = array();
	foreach ($muti_vars as $vars) {
		if (!is_array($vars)) {
			continue;
		}
		$keystr = '';
		$valuestr = '';
		foreach ($vars as $key => $value) {
			if (empty($key)||is_numeric($key)) {
				continue;
			}
			$comma = '';
			if (!empty($keystr)) {
				$comma = ',';
			}
			$keystr .= $comma.$key;
			$valuestr .= $comma.'\''.mysql_escape_string($value).'\'';
		}
		$valuearr[] = $valuestr;
	}
	if (!empty($keystr)) {
		$query = 'insert into '.$table.'('.$keystr.') values';
		$i = 0;
		foreach ($valuearr as $vl) {
			$comma = '';
			if ($i>0) {
				$comma = ',';
			}
			$query .= '('.$valuestr.')';
			$i++;
		}
		$result = $this->g_db_query($query,$db);
		if ($result) {
			return true;
		}else {
			return false;
		}
	}
	return false;
}
/**
 * 替换一条记录
 *
 * @param string $table
 * @param array $vars
 * @param resource $db
 * @return int 影响的行数 或是 错误代码
 */
function g_db_replace($table,$vars,$db=null){
	if (!$table||!is_array($vars)) {
		return false;
	}
	$keystr = '';
	$valuestr = '';
	foreach ($vars as $key => $value) {
		if (empty($key)||is_numeric($key)) {
			continue;
		}
		$comma = '';
		if (!empty($keystr)) {
			$comma = ',';
		}
		$keystr .= $comma.$key;
		$valuestr .= $comma.'\''.mysql_escape_string($value).'\'';
	}
	if (!empty($keystr)) {
		$query = 'replace into '.$table.'('.$keystr.') values('.$valuestr.')';
		$result = $this->g_db_query($query,$db);
		if ($result) {
			return true;
		}else {
			return false;
		}
	}
	return false;
}
/**
 * 替换多条记录
 *
 * @param string $table
 * @param array $muti_vars
 * @param resource $db
 * @return int 影响的行数 或是 错误代码
 */
function g_db_muti_replace($table,$muti_vars,$db=null){
	if (!$table||!is_array($muti_vars)) {
		return false;
	}
	$valuearr = array();
	foreach ($muti_vars as $vars) {
		if (!is_array($vars)) {
			continue;
		}
		$keystr = '';
		$valuestr = '';
		foreach ($vars as $key => $value) {
			if (empty($key)||is_numeric($key)) {
				continue;
			}
			$comma = '';
			if (!empty($keystr)) {
				$comma = ',';
			}
			$keystr .= $comma.$key;
			$valuestr .= $comma.'\''.mysql_escape_string($value).'\'';
		}
		$valuearr[] = $valuestr;
	}
	if (!empty($keystr)) {
		$query = 'replace into '.$table.'('.$keystr.') values';
		$i = 0;
		foreach ($valuearr as $vl) {
			$comma = '';
			if ($i>0) {
				$comma = ',';
			}
			$query .= $comma.'('.$vl.')';
			$i++;
		}
		$result = $this->g_db_query($query,$db);
		if ($result) {
			return true;
		}else {
			return false;
		}
	}
	return false;
}
/**
 * 更新一条记录的指定字段
 *
 * @param string $table
 * @param array $vars
 * @param string $where
 * @param resource $db
 * @return int 影响的行数 或是 错误代码
 */
function g_db_update($table,$vars,$where,$db=null){
	if (!$table||!is_array($vars)) {
		return false;
	}
	$query = "update {$table} set ";
	$i=0;
	foreach ($vars as $key=>$value) {
		$comma = '';
		if ($i>0) {
			$comma = ',';
		}
		$key = mysql_escape_string($key);
		$value = mysql_escape_string($value);
		$query .= $comma." {$key}='{$value}' ";
		$i++;
	}
	$query .= "where {$where} ";
	$result = $this->g_db_query($query,$db);
	if ($result) {
		return true;
	}else {
		return false;
	}
}
function g_db_delete($table,$where,$db=null){
	if (!$table) {
		return false;
	}
	$query = "delete from {$table} where {$where}";
	$result = $this->g_db_query($query,$db);
	if ($result) {
		return true;
	}else {
		return false;
	}
}
/**
 * 查询全部记录
 *
 * @param string $table
 * @param array $keys
 * @param string $where
 * @param int $start
 * @param int $limit
 * @param resource $db
 * @return array or false
 */
function g_db_get_all($table,$keys,$where,$order,$db=null){
	if (!$table) {
		return null;
	}
	if (!$keys) {
		$keystr = '*';
	}else {
		$keystr = join(',',$keys);
	}
	$query = "select {$keystr} from {$table} ";
	if ($where) {
		$query .= " where $where ";
	}
	if ($order) {
		$query .= " order by {$order['by']} {$order['order']}";
	}
	$result = $this->g_db_query($query,$db);
	if (!$result) {
		return null;
	}
	$resultSet = array();
	while ($row = mysql_fetch_assoc($result)) {
		$resultSet[] = $row;
	}
	return $resultSet;
}
/**
 * 查询多条记录
 *
 * @param string $table
 * @param array $keys
 * @param string $where
 * @param int $start
 * @param int $limit
 * @param resource $db
 * @return array or false
 */
function g_db_get_muti($table,$keys,$where,$order,$start,$limit,$db=null){
	if (!$table) {
		return null;
	}
	if (!$keys) {
		$keystr = '*';
	}else {
		$keystr = join(',',$keys);
	}
	$query = "select {$keystr} from {$table} ";
	if ($where) {
		$query .= " where $where ";
	}
	if ($order) {
		$query .= " order by {$order['by']} {$order['order']}";
	}
	$start = intval($start);
	$start = $start>=0?$start:0;
	$limit = intval($limit);
	$limit = $limit>0?$limit:1;
	$query .= " limit {$start},{$limit}";
	$result = $this->g_db_query($query,$db);
	if (!$result) {
		return null;
	}
	$resultSet = array();
	while ($row = mysql_fetch_assoc($result)) {
		$resultSet[] = $row;
	}
	return $resultSet;
}
/**
 * 查询多条记录(1列)
 *
 * @param string $table
 * @param string $key
 * @param string $where
 * @param int $start
 * @param int $limit
 * @param resource $db
 * @return array or false
 */
function g_db_get_column($table,$key,$where,$order,$start,$limit,$db=null){
	if (!$key) {
		return null;
	}
	return g_db_get_muti($table,array($key),$where,$order,$start,$limit,$db);
}
/**
 * 查询一行记录
 *
 * @param string $table
 * @param array $keys
 * @param string $where
 * @param int $start
 * @param int $limit
 * @param resource $db
 * @return array or false
 */
function g_db_get_row($table,$keys,$where,$order,$db=null){
	if (!$table) {
		return null;
	}
	if (!$keys) {
		$keystr = '*';
	}else {
		$keystr = join(',',$keys);
	}
	$query = "select {$keystr} from {$table} ";
	if ($where) {
		$query .= " where $where ";
	}
	if ($order) {
		$query .= " order by {$order['by']} {$order['order']}";
	}
	$query .= ' limit 1';
	$result = $this->g_db_query($query,$db);
	if (!$result) {
		return null;
	}
     
	return mysql_fetch_assoc($result);
}
/**
 * 查询一项纪录
 *
 * @param string $table
 * @param string $key
 * @param string $where
 * @param resource $db
 * @return mixed
 */
function g_db_get_one($table,$key,$where,$order,$db=null){
	if (!$table||!$key) {
		return null;
	}
	$row = g_db_get_row($table,array($key),$where,$order,$db);
	if (isset($row[$key])) {
		return $row[$key];
	}
	return null;
}
/**
 * 获取符合条件的条数
 *
 * @param string $table
 * @param string $key
 * @param string $where
 * @param resource $db
 * @return int
 */
function g_db_count($table,$key,$where,$db=null){
	if (!$table) {
		return 0;
	}
	if (!$key) {
		$key = '*';
	}
	$query = "select count({$key}) as cnt from {$table} where {$where}";
    echo $sql;
	$result = $this->g_db_query($query,$db);
	if (!$result) {
		return 0;
	}
	$row = mysql_fetch_assoc($result);
	return $row['cnt'];
}
/**
 * last_insert_id
 *
 * @param resource $db
 * @return int
 */
function g_db_last_insert_id($db=null){
    $db = $this->get_db_connect();
	return mysql_insert_id($db);
}
/**
 * affected_rows
 *
 * @param resource $db
 * @return int
 */
function g_db_affected_rows($db){
	return mysql_affected_rows($db);
}
}
?>
