<?php
/**
 * 数据管理dao
 * @author zhang.chao
 *
 */
class AdminDao extends BaseDao
{
 
    /**
     *
     * @return BaseConfigDao
     */
    function AdminDao()
    {
        parent::BaseDao();
    }
    function getAdminList(){
		$sql="select *  from  OA_admin  where  del_flag<>1";
		$result=$this->g_db_query($sql);
		return $result;
    }
    function checklogin($name,$pass){
    	$sql="select *  from  OA_admin  where  name='{$name}' and password='{$pass}'";
		$result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
    }
    /**
     * 添加管理员
     * @param $admin
     */
    function addAdmin($admin){
    	$sql="insert into OA_admin (name,admin_type,password,create_time,memo) values ('{$admin['name']}',{$admin['admin_type']},'{$admin['password']}',now(),'{$admin['memo']}')";
		$result=$this->g_db_query($sql);
		return $result;
    }
    /**
     * 修改管理员为删除状态
     * @param $adminId
     */
    function updateAdminToDelete($adminId){
    	$sql="update OA_admin set del_flag=1 where  id=$adminId";
		$result=$this->g_db_query($sql);
		return $result;
    }

    /**
     * 得到密码
     *@param $name
     */
    function getPass($name){
    	$sql="select password  from  OA_admin  where   name='{$name}'";
		$result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
    }
    /**
     * 更新密码
     *@param $name,$pass
     */
    function updatePass($name,$pass){
    	$sql="update OA_admin set password = '{$pass}'   where   name='{$name}'";
    	$result=$this->g_db_query($sql);
    	return $result;
    }
    
    
/**
     * 得到操作日志列表
     * @param $listwhere
     */
    function getOpLogList($listwhere){
    	$sql="select OA_log.* ,OA_admin.name,OA_admin.admin_type  from OA_log,OA_admin where OA_log.who=OA_admin.id  $listwhere";
    	$result=$this->g_db_query($sql);
		return $result;
    }
}
?>
