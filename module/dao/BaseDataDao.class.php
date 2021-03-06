<?php
/**
 * 数据管理dao
 * @author zhang.chao
 *
 */
class BaseDataDao extends BaseDao
{

    /**
     *
     * @return BaseConfigDao
     */
    function BaseDataDao()
    {
        parent::BaseDao();
    }
    function addShenfenData ($shenfenType) {
        $sql="insert into OA_base_shenfenType (type_name,op_id,type_id,create_time)
        values ('{$shenfenType['type_name']}',{$shenfenType['op_id']},{$shenfenType['type_id']},now())";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function deleteShenfen($id) {
        $sql="delete from OA_base_shenfenType  where id = $id";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getShenfenDataByName ($name) {
        $sql="select *  from OA_base_shenfenType  where type_name = '{$name}'";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function getShenfenTypeList () {
        $sql="select OA_admin.name ,OA_base_shenfenType.*  from OA_base_shenfenType , OA_admin where OA_base_shenfenType.op_id = OA_admin.id ";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function updateShenfenData ($shenfenType) {
        $sql="update OA_base_shenfenType  set  type_name = '{$shenfenType['type_name']}',type_id = {$shenfenType['type_id']} where id = {$shenfenType['id']}";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getCompanyRootIdByCompanyId($companyId) {
        $sql ="select * from OA_department_tree where company_id= $companyId";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function getChildNodeDataByPid($parentId) {
        $sql ="select * from OA_department_tree where pid=$parentId";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getTreeNodeDataById($id) {
        $sql ="select * from OA_department_tree where  id=$id";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function addDepartmentTreeData($data) {
        $sql ="insert into OA_department_tree (pid,name,create_time,company_id) values ({$data['pid']},'{$data['name']}',now(),{$data['company_id']})";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getDepartmentsByCompanyId ($companyId) {
        $sql ="select * from OA_department_tree where  pid = $companyId";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getDepartmentsById ($departId) {
        $sql ="select * from OA_department_tree where  id = $departId";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function getDepartmentByNameAndComId($daprtName,$companyId) {
        $sql ="select * from OA_department_tree where name='{$daprtName}' and pid = $companyId";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }

    function addEmployTreeData($data) {
        $sql ="insert into OA_department_tree (pid,name,create_time,company_id,employ_id,is_employ)
        values ({$data['pid']},'{$data['name']}',now(),{$data['company_id']},{$data['employ_id']},1)";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function eitDepartmentTreeData($data) {
        $sql ="update  OA_department_tree  set name = '{$data['name']}' where id = {$data['id']}";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function delDepartmentTreeData($data) {
        $sql ="delete from OA_department_tree   where id = {$data['id']}";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function isParentNode($id) {
        $sql ="select count(id) as cnt from OA_department_tree where pid=$id";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function getEmlistbyComname($comName){
        $sql="select *  from  OA_employ  where 1=1";
        if($comName!=null){
            $sql.=" and e_company = '$comName'";
        }
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getEmlistbyDepartId($departId){
        $sql="select *  from  OA_employ  where department_id = $departId";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getCountEmlistbyDepartId($departId){
        $sql="select count(*) as cnt  from  OA_employ  where department_id = $departId";
        $result=$this->g_db_query($sql);
        $count = mysql_fetch_array($result);
        return $count['cnt'];
    }
    function getEmployById($eid){
        $sql="select *  from OA_employ where id=$eid";
        //echo $sql;
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function getNoticeByCompanyId($comId) {
        $sql = "select *  from OA_notice where company_id = $comId order by update_time desc";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getZiduanListByComId($comId,$departmentId = null) {
        $sql = "select *  from OA_ziduan where company_id = $comId ";
        if ($departmentId)  {
            $sql .=" and department_id = $departmentId  ";
        }
        $sql .= "order by update_time ";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getZiduanListByName($name,$companyId) {
        $sql = "select *  from OA_ziduan where zd_name = '{$name}' and company_id = $companyId";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function getZiduanById($id) {
        $sql = "select *  from OA_ziduan where id = $id";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function addSalZiduan($ziduan) {
        $sql = "insert into  OA_ziduan  (zd_name,department_id,company_id,zd_type,update_time)  values
        ('{$ziduan['zd_name']}',{$ziduan['department_id']},{$ziduan['company_id']},{$ziduan['zd_type']},now())";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function delSalZiduan($id) {
        $sql = "delete from  OA_ziduan  where id = $id";
        $result=$this->g_db_query($sql);
        return $result;
    }
}
?>
