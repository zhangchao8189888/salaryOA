<?php
/**
 *员工dao
 * @author zhang.chao
 *
 */
class CompanyDao extends BaseDao
{

    /**
     *
     * @return BaseConfigDao
     */
    function CompanyDao()
    {
        parent::BaseDao();
    }
    function addCompany($company){
        $sql="insert into OA_company
		(
		company_code,company_name,com_contact,contact_no,company_address,com_bank,bank_no,company_level,company_type,company_status,
		add_time,update_time
		)
		 values ('{$company["company_code"]}','{$company["company_name"]}','{$company["com_contact"]}','{$company["contact_no"]}',
		 '{$company["company_address"]}','{$company["com_bank"]}','{$company["bank_no"]}',
		{$company["company_level"]},{$company["company_type"]},1,now(),now())";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function updateCompany($company){
        $sql="update OA_company set
		company_name = '{$company["company_name"]}', com_contact ='{$company["com_contact"]}',
		contact_no = '{$company["contact_no"]}',
		 company_address = '{$company["company_address"]}',
		 bank_no = '{$company["bank_no"]}',
		company_level = {$company["company_level"]},
		company_type = {$company["company_type"]},update_time = now(),company_status = {$company["company_status"]} where id = {$company["id"]}";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getCompanyList($where,$startIndex,$pagesize) {
        $sql="select * from OA_company where 1=1";
        if ($where) {
            $sql.=$where;
        }
        $sql.=" order by update_time desc limit $startIndex,$pagesize";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getCompanyListAll($where) {
        $sql="select * from OA_company ";
        if ($where) {
            $sql.=$where;
        }
        $sql.= ' and company_status=1';
        $sql.=" order by update_time desc ";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getDepartmentAll($where) {

        $sql ="select * from OA_department_tree ";

        if ($where) {
            $sql.=$where;
        }
        $sql.=" order by create_time asc ";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getCompanyById($id) {
        $sql="select * from OA_company where id=$id";
        $result=$this->g_db_query($sql);
        return mysql_fetch_assoc($result);
    }
    function getCompanyByName($name) {
        $sql="select * from OA_company where company_name='{$name}'";
        $result=$this->g_db_query($sql);
        return mysql_fetch_assoc($result);
    }
}
?>
