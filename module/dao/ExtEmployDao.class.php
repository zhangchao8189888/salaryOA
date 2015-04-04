<?php
/**
 *员工dao
 * @author zhang.chao
 *
 */
class ExtEmployDao extends BaseDao
{

    /**
     *
     * @return BaseConfigDao
     */
    function ExtEmployDao()
    {
        parent::BaseDao();
    }

    function getEmployListCount($where = null){
        $id = $_SESSION ['admin'] ['id'];

        $sql="select count(*) as cnt from  OA_employ  where convert( e_company using utf8) in (select c.company_name from OA_company c,OA_admin_company a  where a.companyId = c.id  and  a.adminId = $id)";
        if($where['e_company']!=null){
            $sql.=" and e_company like '%{$where['e_company']}%'";
        }
        if($where['eStat']!=null){
            $sql.=" and e_state='{$where['eStat']}'";
        }
        if($where['e_name']!=null){
            $sql.=" and e_name like '%{$where['e_name']}%'";
        }
        if($where['e_num']!=null){
            $sql.=" and e_num like '%{$where['e_num']}%'";
        }
        if($where['e_type']!=null){
            $sql.=" and e_type = '{$where['e_type']}'";
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }

    function getEmployListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null){
        $id = $_SESSION ['admin'] ['id'];
        $sql="select *  from  OA_employ  where convert( e_company using utf8) in (select c.company_name from OA_company c,OA_admin_company a  where a.companyId = c.id  and  a.adminId = $id)";
        if($where['e_company']!=null){
            $sql.=" and e_company like '%{$where['e_company']}%'";
        }
        if($where['eStat']!=null){
            $sql.=" and e_state='{$where['eStat']}'";
        }
        if($where['e_name']!=null){
            $sql.=" and e_name like '%{$where['e_name']}%'";
        }
        if($where['e_num']!=null){
            $sql.=" and e_num like '%{$where['e_num']}%'";
        }
        if($where['e_type']!=null){
            $sql.=" and e_type = '{$where['e_type']}'";
        }
        if ($sort) {
            $sql .= " order by $sort";
        }
        if($where['contractinfo']==null){
            if ($start >= 0 && $limit) {
                $sql .= " limit $start,$limit";
            }
        }
        $result=$this->g_db_query($sql);
        return $result;
    }

    function updateInsurance($id,$e_hetong_date,$e_hetongnian){
        $sql="UPDATE OA_employ  SET e_hetong_date='$e_hetong_date',e_hetongnian='$e_hetongnian' where id=$id";
        $result=$this->g_db_query($sql);
        return $result;
    }

    function updateEm($employ){
        $sql="update OA_employ  set
		e_name='{$employ["e_name"]}',e_company='{$employ["e_company"]}',
		e_num='{$employ["e_num"]}',bank_name='{$employ["bank_name"]}',
		bank_num='{$employ["bank_num"]}',e_type='{$employ["e_type"]}',
		shebaojishu={$employ["shebaojishu"]},gongjijinjishu={$employ["gongjijinjishu"]},
		laowufei={$employ["laowufei"]},canbaojin={$employ["canbaojin"]},e_hetongnian={$employ["e_hetongnian"]},e_hetong_date='{$employ["e_hetong_date"]}',
		danganfei={$employ["danganfei"]},memo='{$employ["memo"]}' where id={$employ["id"]}
		";
        $result=$this->g_db_query($sql);
        return $result;
    }

    function updateEmployNoByid($eid,$enum){
        $sql="update OA_employ  set
		e_num='{$enum}' where id={$eid}
		";
        $result=$this->g_db_query($sql);
        return $result;
    }

    function searchInsurance($id){
        $sql="select * from OA_employ where id=$id";
        $result=$this->g_db_query($sql);
        return $result;
    }
}
?>
