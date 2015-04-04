<?php
/**
 * 数据管理dao
 * @author zhang.chao
 *
 */
class FinanceDao extends BaseDao
{
 
    /**
     *
     * @return BaseConfigDao
     */
    function FinanceDao()
    {
        parent::BaseDao();
    }
    /*function getAdminList(){
		$sql="select *  from  OA_admin  where  del_flag<>1";
		$result=$this->g_db_query($sql);
		return $result;
    }
    function checklogin($name,$pass){
    	$sql="select *  from  OA_admin  where  name='{$name}' and password='{$pass}'";
		$result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
    }*/
    function getFinanceList($salaryTimeId){
		$sql="select *  from  OA_bill  where salaryTime_id=$salaryTimeId" ;
		$result=$this->g_db_query($sql);
		return $result;
    }
	function searchSalTimeListBySalTime($salTime){
		$id= $_SESSION['admin']['id'];
		$sql="SELECT st.*,c.company_name FROM OA_salarytime st,OA_company c,OA_admin_company a WHERE st.companyId=c.id AND st.salaryTime='{$salTime}'
		AND a.companyId = c.id AND a.adminId = $id ";
    	$result=$this->g_db_query($sql);
		return $result;
	}
	function  searchFaBill($salaryTimeId){
		$sql="select *  from  OA_bill  where salaryTime_id=$salaryTimeId and bill_state=4 "  ;
		$result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
    }

    /**
     * financedao审核公司
     */
    function searchCheckCompanyListPage($start=NULL,$limit=NULL,$sort=NULL,$where){
    	$sql="select * from OA_checkcompany where 1=1";

    		if($where['companyName']!=""){
    			$sql.=" and company_name like '%{$where['companyName']}%' ";
    		}

    	if($sort){
    		$sql.=" order by $sort";
    	}
    	if($start>=0&&$limit){
    		$sql.=" limit $start,$limit";
    	}

    	$result=$this->g_db_query($sql);
    	return $result;
    }
    function searchCheckCompanyListCount($where){
    	$sql="select count(*) as cnt from OA_checkcompany where 1=1";
    	if($where!=null){
    		if($where['companyName']!=""){
    			$sql.=" and company_name like '%{$where['companyName']}%' ";
    		}
    	}
    	$result=$this->g_db_query($sql);
    	if (!$result) {
    		return 0;
    	}
    	$row = mysql_fetch_assoc($result);
    	return $row['cnt'];
    }
    function getCheckCompanyById($comId) {
        $sql = "select *  from OA_checkcompany where  id=$comId";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    function companyClear($id,$company){

        $sql = "insert into OA_company (company_name,company_address) values('{$company['company_name']}','{$company['company_address']}')";
        $result = $this->g_db_query ( $sql );
        if ($result) {
            $sql="delete from OA_checkcompany where id =$id";
            $result = $this->g_db_query ( $sql );
            if ($result) {
                return $this->g_db_last_insert_id ();
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * financedao 查看个税
     */
    function searchTaxListCount($where){
        $id = $_SESSION ['admin'] ['id'];
        $sql="select count(*) as cnt from OA_admin_company a,OA_company b where a.adminId=$id and a.companyId=b.id";
        if($where!=null){
            if($where['companyName']!=""){
                $sql.=" and company_name like '%{$where['companyName']}%' ";
            }
        }
        $result=$this->g_db_query($sql);
        if (!$result) {
            return 0;
        }
        $row = mysql_fetch_assoc($result);
        return $row['cnt'];
    }

    function searchTaxListPage($start=NULL,$limit=NULL,$sort=NULL,$where){
        $id = $_SESSION ['admin'] ['id'];
        $sql="select * from OA_admin_company a,OA_company b where a.adminId=$id and a.companyId=b.id";

        if($where['companyName']!=""){
            $sql.=" and company_name like '%{$where['companyName']}%' ";
        }

        if($sort){
            $sql.=" order by b.$sort";
        }
        if($start>=0&&$limit){
            $sql.=" limit $start,$limit";
        }
        $result=$this->g_db_query($sql);
        return $result;
    }
    function searchTaxTimeByDateAndComId($date, $comId,$type) {
        $sql = "select *  from OA_gesui where salTime like '%{$date}%' and comId=$comId and  geSui_type='$type'";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }

    function searchBillBySalaryTimeId($salaryTimeId, $billType = null) {
        $sql = "select *  from OA_bill where salaryTime_id=$salaryTimeId ";
        if ($billType != null) {
            $sql .= " and bill_type=$billType ";
        }
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    /**
     * 客服首页dao 获得count
     */
    function searhManageComCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT count(distinct company_name) as cnt   from OA_company c,OA_admin_company a,OA_salarytime b  where 1=1
  and a.companyId = c.id and b.companyId=c.id and  a.adminId = $id";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['searchType'] != "") {
                if ($where ['searchType'] == 1) {
                    if($where ['$salTime']!=""){
                        $sql .= " and b.salaryTime='{$where ['$salTime']}' ";
                    }
                } elseif ($where ['searchType'] == 2) {
                    $sql .= " and b.op_salaryTime>='{$where ['$salTime']}' and b.op_salaryTime<='{$where ['dateEnd']}' ";
                }elseif ($where ['searchType'] == 3) {
                    $sql .= "and b.salaryTime>='{$where ['$salTime']}' and b.salaryTime<='{$where ['dateEnd']}' ";
                }
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }

    /**
     * 客服首页dao 获得分页数据
     */
    function searhManageComPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT  distinct c.id,c.company_name from OA_company c,OA_admin_company a,OA_salarytime b  where 1=1
        and a.companyId = c.id and b.companyId=c.id and a.adminId = $id ";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['searchType'] != "") {
                if ($where ['searchType'] == 1) {
                    if($where ['$salTime']!=""){
                        $sql .= " and b.salaryTime='{$where ['$salTime']}' ";
                    }
                } elseif ($where ['searchType'] == 2) {
                    $sql .= " and b.op_salaryTime>='{$where ['$salTime']}' and b.op_salaryTime<='{$where ['dateEnd']}' ";
                }elseif ($where ['searchType'] == 3) {
                    $sql .= " and b.salaryTime>='{$where ['$salTime']}' and b.salaryTime<='{$where ['dateEnd']}' ";
                }
            }
        }
        if ($sort) {
            $sql .= " order by $sort";
        }
        if ($start >= 0 && $limit) {
            $sql .= " limit $start,$limit";
        }
        // $sql.=" order by op_salaryTime desc ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    
}
?>
