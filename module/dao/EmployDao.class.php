<?php
/**
 *员工dao
 * @author zhang.chao
 *
 */
class EmployDao extends BaseDao
{
 
    /**
     *
     * @return BaseConfigDao
     */
    function EmployDao()
    {
        parent::BaseDao();
    }
    function addEm($employ){
		$sql="insert into OA_employ 
		(e_name,e_company_id,e_company,e_num,bank_name,bank_num,e_type,shebaojishu,gongjijinjishu,laowufei,canbaojin,danganfei,memo,e_hetongnian,e_hetong_date,e_status,department_id,e_sort,update_time)
		 values ('{$employ["e_name"]}',{$employ["e_company_id"]},'{$employ["e_company"]}','{$employ["e_num"]}','{$employ["bank_name"]}','{$employ["bank_num"]}',{$employ["e_type"]},
		{$employ["shebaojishu"]},{$employ["gongjijinjishu"]},{$employ["laowufei"]},{$employ["canbaojin"]},{$employ["danganfei"]},'{$employ["memo"]}',{$employ["e_hetongnian"]},'{$employ["e_hetong_date"]}',
		{$employ["e_status"]},{$employ["department_id"]},{$employ["e_sort"]},now())";
		$result=$this->g_db_query($sql);
		return $result;
    }
    function getEmployListByComId ($comId,$departmentId = null){
        $sql="select * from OA_employ where e_company_id = $comId";
        if ($departmentId) {
            $sql .=" and department_id = $departmentId";
        }
        $sql.=" order by e_sort";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getEmployList($where,$startIndex,$pagesize) {
        $sql="select OA_employ.*,OA_company.company_name from OA_employ,OA_company where 1=1  and OA_employ.e_company_id = OA_company.id";
        if ($where) {
            $sql.=$where;
        }
        $sql.=" order by OA_employ.update_time desc limit $startIndex,$pagesize";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getEmlistbyComname($comName,$eStat=null,$empName=null,$empNo=null){
    	echo  $eStat;
    	$sql="select *  from  OA_employ  where 1=1";
        if($comName!=null){
    		$sql.=" and e_company like '%$comName%'";
    	}
    	if($eStat!=null){
    		$sql.=" and e_status=$eStat";
    	}
        if($empName!=null){
    		$sql.=" and e_name like '%$empName%'";
    	}
        if($empNo!=null){
    		$sql.=" and e_num = '{$empNo}'";
    	}
    	$result=$this->g_db_query($sql);
    	return $result;
    }
//员工列表BY孙瑞鹏
    function getEmlistbyComnameExt($comName,$eStat=null,$empName=null,$empNo=null){
        $sql="select *  from  OA_employ  where 1=1";
        if($comName!=null){
            $sql.=" and e_company like '%$comName%'";
        }
        if($eStat!=null){
            $sql.=" and e_status=$eStat";
        }
        if($empName!=null){
            $sql.=" and e_name like '%$empName%'";
        }
        if($empNo!=null){
            $sql.=" and e_num = '{$empNo}'";
        }
        $result=$this->g_db_query($sql);
        return $result;
    }
    //BY孙瑞鹏，取得是否残疾
    function getCanjiren($eNo) {
        $sql = "select e_teshu_state  from OA_employ  where e_num='{$eNo}'";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    function getEmlistbyHetongriqi($comName,$hetongriqi,$hetongriqiEnd){
    	$sql="select *  from  OA_employ  where e_company like '%$comName%' and e_hetong_date>='$hetongriqi' and e_hetong_date<='$hetongriqiEnd'";
    	$result=$this->g_db_query($sql);
    	return $result;
    }
    function delEmployById ($id) {
        $sql="delete  FROM OA_employ WHERE id=$id";
        //echo $sql;
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getEmployById($eid){
    	$sql="select oc.company_name,oe.*  from OA_employ oe,OA_company oc where oe.e_company_id = oc.id and oe.id=$eid";
    	$result=$this->g_db_query($sql);
    	return mysql_fetch_array($result);
    }
    function updateEmployNoByid($eid,$enum){
    	$sql="update OA_employ  set 
		e_num='{$enum}' where id={$eid}
		";
		$result=$this->g_db_query($sql);
		return $result;
    }
    function updateEm($employ){
    	$sql="update OA_employ  set 
		e_name='{$employ["e_name"]}',e_company='{$employ["e_company"]}',e_company_id={$employ["e_company_id"]},
		e_num='{$employ["e_num"]}',bank_name='{$employ["bank_name"]}',
		bank_num='{$employ["bank_num"]}',e_type='{$employ["e_type"]}',department_id={$employ["department_id"]},
		shebaojishu={$employ["shebaojishu"]},gongjijinjishu={$employ["gongjijinjishu"]},e_status={$employ["e_status"]},
		laowufei={$employ["laowufei"]},canbaojin={$employ["canbaojin"]},e_hetongnian={$employ["e_hetongnian"]},e_hetong_date='{$employ["e_hetong_date"]}',
		danganfei={$employ["danganfei"]},memo='{$employ["memo"]}',update_time = now() where id={$employ["id"]}
		";
		$result=$this->g_db_query($sql);
		return $result;
    }
    function delEmploy($e_id){
    	$sql="delete from OA_employ where id=$e_id";
    	$result=$this->g_db_query($sql);
		return $result;
    }
    function searhCompanyList(){
    	$sql="select e_company  from OA_employ group by e_company";
    	$result=$this->g_db_query($sql);
		return $result;
    }
    function searhCompanyListByComany(){
        $id = $_SESSION ['admin'] ['id'];
    	$sql="select company_name from OA_company c,OA_admin_company a  where
   a.companyId = c.id  and  a.adminId = $id";
    	$result=$this->g_db_query($sql);
		return $result;
    }
    function getCompanyById($comId){
    	$sql="select *  from OA_company where  id=$comId";
    	$result=$this->g_db_query($sql);
		return mysql_fetch_array($result);
    }
    function delEmployByComName($cname){
    	$sql="delete  from OA_employ  where  e_company='$cname'";
    	//echo  $sql;
    	$result=$this->g_db_query($sql);
		return $result;
    }
    function changeEmployStat($type){
    	$sql="update  OA_employ set ";
    }
    function changeEmpIndexByEnum($new_index,$eid){
        $sql="update  OA_employ set e_sort = $new_index where e_num = $eid";

        $result=$this->g_db_query($sql);
        return $result;
    }
    function changeEmpIndexById($new_index,$eid){
        $sql="update  OA_employ set e_sort = $new_index where id = $eid";

        $result=$this->g_db_query($sql);
        return $result;
    }
    function changeEmpSortBySort($old_index,$new_index){
        $sql="update  OA_employ set e_sort = $new_index where e_sort = $old_index";

        $result=$this->g_db_query($sql);
        return $result;
    }
}
?>
