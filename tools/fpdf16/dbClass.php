<?php
date_default_timezone_set("Asia/Shanghai");
//数据库操作的类库	
	class dbClass{ //开始创建数据库类
		var $username;//定义登陆数据库的名字
		var $password;//登陆数据库的密码
		var $database;//所要选择的数据库
		var $hostname;//mysql数据库的名字
		var $link;//连接数据库的变量
		var $result;//结果集赋给$result
		function dbClass($username,$password,$database,$hostname){//利用构造方法给变量赋值
			$this->username=$username;
			$this->password=$password;
			$this->database=$database;
			$this->hostname=$hostname;
		}
		function connect(){ //这个函数用于连接数据库
			$this->link=@mysql_connect($this->hostname,$this->username,$this->password) or die("对不起，连接数据库失败");
			return $this->link;
		}
		function select(){ //这个函数用于选择数据库
			@mysql_select_db($this->database,$this->link);
		}
		function query($sql){ //这个函数用于送出查询语句并返回结果，常用。
			@mysql_query("SET NAMES 'utf8'"); 
			if($this->result=@mysql_query($sql,$this->link)){ 
				
				return $this->result;
			}
			//else {				//这里是显示SQL语句的错误信息，主要是设计阶段用于提示。正式运行阶段可将下面这句注释掉。
				//echo "<br>SQL语句错误： <font color=red>$sql</font> <BR><BR>错误信息： ".mysql_error();
				//return false;
			//}
		}
/*
以下函数用于从结果取回数组，一般与 while()循环、$db->query($sql) 配合使用，例如：
$result=query("select * from mytable");
while($row=$db->getarray($result)){
echo "$row[id] ";
}
*/
	function getarray($result){
		return @mysql_fetch_array($result);
	}
/*
以下函数用于取得SQL查询的第一行，一般用于查询符合条件的行是否存在，例如：
用户从表单提交的用户名$username、密码$password是否在用户表“user”中，并返回其相应的数组：
if($user=$db->getfirst("select * from user where username='$username' and password='$password' "))
echo "欢迎 $username ，你的ID是 $user[id] 。";
else
echo "用户名或密码错误！";
*/
	function getfirst($sql){
	return @mysql_fetch_array($this->query($sql));
	}
/*
以下函数返回符合查询条件的总行数，例如用于分页的计算等要用到，例如：
$totlerows=$db->getcount("select * from mytable");
echo "共有 $totlerows 条信息。";
*/
	function getcount($sql){
		return @mysql_num_rows($this->query($sql)); 
	}

/*
以下函数用于更新数据库，例如用户更改密码：
$db->update("update user set password='$new_password' where userid='$userid' ");
*/
	function update($sql){
		return $this->query($sql);
	}

/*
以下函数用于向数据库插入一行，例如添加一个用户：
$db->insert("insert into user (userid,username,password) values (null,'$username','$password')");
*/
	function insert($sql){
		return $this->query($sql);
	}
	function getid(){ //这个函数用于取得刚插入行的id
		return @mysql_insert_id();
	}
	//获取文件后缀名
	function getFileExt($file_name){
        while($dot = strpos($file_name, "."))
        {
                $file_name = substr($file_name, $dot+1);
        }
        return $file_name;
	} 
}
//开始进行实例，调用构造方法
$db=new dbClass("root","123456","xifei2011","localhost");
$db->connect();//链接数据库
$db->select();//选择数据库
//单值查询
function get_vclassname($yx_name,$yx_biao,$yx_idname,$yx_id)
{
	$sqlyxc = "SELECT ".$yx_name." FROM ".$yx_biao." where ".$yx_idname."='".$yx_id."'";
	@mysql_query("SET NAMES 'utf8'"); 
	$resyxd = mysql_query($sqlyxc);
	$rowyxd = mysql_fetch_array($resyxd);
	return $rowyxd[$yx_name];
}
?>