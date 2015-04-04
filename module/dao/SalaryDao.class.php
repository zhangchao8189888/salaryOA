<?php
/**
 * 工资dao
 * @author zhang.chao
 *
 */
class SalaryDao extends BaseDao {

    /**
     *
     * @return BaseConfigDao
     */
    function SalaryDao() {
        parent::BaseDao ();
    }
    function getDianfuOrYanfu ($yandianfu) {
        $sql = "select oe.e_name,oe.e_num ,oy.*  from OA_yanOrDian oy,OA_employ oe where oy.employ_num = oe.e_num and oy.salary_time_id = {$yandianfu['salTimeId']} ";
        if ($yandianfu['yanOrdian_type']) {
            $sql .= " and oy.yanOrdian_type = {$yandianfu['yanOrdian_type']}";
        }
        $result=$this->g_db_query($sql);
        return $result;
    }
    function saveDianfuOrYanfu ($yanDianfu) {
        $sql = " insert into OA_yanOrDian (salary_time_id,salary_id,employ_num,per_shiye,per_yiliao,per_yanglao,
        per_gongjijin,com_shiye,com_yiliao,com_yanglao,com_gongshang,com_shengyu,com_gongjijin,yanOrdian_type)
        values ({$yanDianfu['salary_time_id']},{$yanDianfu['salary_id']},'{$yanDianfu['employ_num']}',
        {$yanDianfu['per_shiye']},{$yanDianfu['per_yiliao']},{$yanDianfu['per_yanglao']},
        {$yanDianfu['per_gongjijin']},{$yanDianfu['com_shiye']},{$yanDianfu['com_yiliao']},{$yanDianfu['com_yanglao']},
        {$yanDianfu['com_gongshang']},{$yanDianfu['com_shengyu']},{$yanDianfu['com_gongjijin']},
        {$yanDianfu['yanOrdian_type']}
        )
        ";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getFukuandanList ($where  = null) {
        $sql = "select oa.name as admin_name,oc.company_name,os.salaryTime,of.*  from OA_admin oa, OA_company oc,OA_salarytime os,OA_fukuandan of where
of.company_id = oc.id and of.salTime_id = os.id  and of.op_id =oa.id ";
        if ($where['company_name']) {
            $sql .= " and oc.company_name like '%{$where['company_name']}%'";
        }
        $sql .= " order by update_time desc";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getFukuandanById ($id) {
        $sql = "select oa.name as admin_name,oc.company_name,os.salaryTime,of.*  from OA_admin oa, OA_company oc,OA_salarytime os,OA_fukuandan of where
of.company_id = oc.id and of.salTime_id = os.id  and of.op_id =oa.id and of.id = $id";
        $result=$this->g_db_query($sql);
        return mysql_fetch_array($result);
    }
    function getFukuandanListCount ($where = null) {
        $sql = "select count(of.id) as cnt  from OA_admin oa, OA_company oc,OA_salarytime os,OA_fukuandan of where
of.company_id = oc.id and of.salTime_id = os.id  and of.op_id =oa.id ";
        if ($where['company_name']) {
            $sql .= " and oc.company_name like '%{$where['company_name']}%'";
        }
        $result=$this->g_db_query($sql);
        $sum = mysql_fetch_array($result);
        return $sum['cnt'];
    }

    function getShoukuanList () {
        $sql = "select oa.name as admin_name,oc.company_name,os.salaryTime,of.*  from OA_admin oa, OA_company oc,OA_salarytime os,OA_shoukuan of where
of.company_id = oc.id and of.salaryTime_id = os.id  and of.op_id =oa.id ;";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getFukuandanBySalTimeId ($salTime_id) {
        $sql="select * from OA_fukuandan where salTime_id = $salTime_id";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    function saveFukuandan($fukuandan) {
        $sql="insert into OA_fukuandan
        (company_id,salTime_id,salSumValue,
        op_id,file_path,create_time,update_time,
        fukuan_status,memo)
        values ({$fukuandan['company_id']},{$fukuandan['salTime_id']},
        {$fukuandan['salSumValue']},{$fukuandan['op_id']},'{$fukuandan['file_path']}',
        now(),now(),{$fukuandan['fukuan_status']},'{$fukuandan['memo']}'
        )";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function updateFukuandan ($fukuandan) {
        $sql="update OA_fukuandan
        set company_id = {$fukuandan['company_id']},salTime_id = {$fukuandan['salTime_id']},
        salSumValue = {$fukuandan['salSumValue']},
        op_id = {$fukuandan['op_id']},
        create_time = now(),update_time = now(),memo = '{$fukuandan['memo']}' ";
        if ($fukuandan['file_path']) {
            $sql .= " ,file_path = '{$fukuandan['file_path']}'";
        }
        $sql .= "where id = {$fukuandan['id']}";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function updateFukuandanStatus($fukuandan) {
        $sql="update OA_fukuandan set fukuan_status = {$fukuandan['fukuan_status']} where id = {$fukuandan['id']}";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function saveShoukuan($shoukuan) {
        $sql="insert into OA_shoukuan
        (shou_code,company_id,salaryTime_id,shoukuanjin,
        laowufei,pay_type,piao_json,shoukuan_person_name,
        op_id,add_time,update_time,
        shou_status,more,file_path)
        values ('{$shoukuan['shou_code']}',{$shoukuan['company_id']},{$shoukuan['salaryTime_id']},
        {$shoukuan['shoukuanjin']},{$shoukuan['laowufei']},{$shoukuan['pay_type']},'{$shoukuan['piao_json']}',
        '{$shoukuan['shoukuan_person_name']}',
        {$shoukuan['op_id']},now(),now(),{$shoukuan['shou_status']},'{$shoukuan['more']}','{$shoukuan['file_path']}'
        )";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function updateShoukuan($fukuan) {
        $sql="update OA_shoukuan
        set
        company_id = {$fukuan['company_id']},
        salaryTime_id = {$fukuan['salaryTime_id']},
        shoukuanjin = {$fukuan['shoukuanjin']},
        laowufei = {$fukuan['laowufei']},
        pay_type = {$fukuan['pay_type']},
        piao_json = '{$fukuan['piao_json']}',
        shoukuan_person_name ='{$fukuan['shoukuan_person_name']}',
        op_id = {$fukuan['op_id']},
        update_time = now(),
        shou_status = {$fukuan['shou_status']},
        more = '{$fukuan['more']}',update_time = now(),
        where id = {$fukuan['id']}";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getFukuantongzhiList () {
        $sql = "select oa.name as admin_name,oc.company_name,os.salaryTime,of.*  from OA_admin oa, OA_company oc,OA_salarytime os,OA_fukuantongzhi of where
of.company_id = oc.id and of.salary_time_id = os.id  and of.op_id =oa.id ;";
        $result=$this->g_db_query($sql);
        return $result;
    }

    function saveFukuanTongzhi($fukuan) {
        $sql="insert into OA_fukuantongzhi
        (fu_code,company_id,salary_time_id,yingfu_money,
        laowufei_money,fapiao_id_json,jieshou_person_id,jieshou_person_name,
        op_id,add_time,update_time,
        zhifu_status,more)
        values ('{$fukuan['fu_code']}',{$fukuan['company_id']},{$fukuan['salary_time_id']},
        {$fukuan['yingfu_money']},{$fukuan['laowufei_money']},'{$fukuan['fapiao_id_json']}',
        {$fukuan['jieshou_person_id']},'{$fukuan['jieshou_person_name']}',
        {$fukuan['op_id']},now(),now(),
        0,'{$fukuan['more']}'
        )";
        //echo $sql;
        $result=$this->g_db_query($sql);
        return $result;
    }
    function updateFukuanTongzhi($fukuan) {
        $sql="update OA_fukuantongzhi
        set
        company_name = '{$fukuan['company_name']}',
        salary_time_id = {$fukuan['salary_time_id']},
        salary_time = '{$fukuan['salary_time']}',
        yingfu_money = {$fukuan['yingfu_money']},
        laowufei_money = {$fukuan['laowufei_money']},
        fapiao_id_json = '{$fukuan['fapiao_id_json']}',
        jieshou_person_id = {$fukuan['jieshou_person_id']},
        jieshou_person_name ='{$fukuan['jieshou_person_name']}',
        zhifu_status = {$fukuan['zhifu_status']},
        more = '{$fukuan['more']}',update_time = now(),
        where id = {$fukuan['id']}";
        $result=$this->g_db_query($sql);
        return $result;
    }
    function getAdminList() {
        $sql = "select *  from  DM_Admin  where  del_flag=0";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function getAllComByComId() {
        $sql = "select *  from  OA_company  where  check=0";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function saveSalaryTime($salaryTime) {
        $sql = "insert into OA_salarytime (companyId,salaryTime,op_salaryTime,mark) values({$salaryTime['companyId']}
    	,'{$salaryTime['salaryTime']}','{$salaryTime['op_salaryTime']}','{$salaryTime['mark']}');";
        $list = $this->g_db_query ( $sql );
        if ($list) {
            return $this->g_db_last_insert_id ();
        } else {
            return false;
        }
    }
    function saveSalaryNianTime($salaryTime) {
        $sql = "insert into OA_salarytime_other (companyId,salaryTime,op_salaryTime,salaryType) values({$salaryTime['companyId']}
    	,'{$salaryTime['salary_time']}','{$salaryTime['op_salaryTime']}',{$salaryTime['salaryType']});";
        $list = $this->g_db_query ( $sql );
        if ($list) {
            return $this->g_db_last_insert_id ();
        } else {
            return false;
        }
    }
    function saveSalary($salary) {
        $sql = "insert  into  OA_salary (employid,salaryTimeId,per_yingfaheji,per_shiye,per_yiliao,per_yanglao,per_gongjijin,per_daikoushui
    	,per_koukuangheji,per_shifaheji,com_shiye,com_yiliao,com_yanglao,com_gongshang,com_shengyu,com_gongjijin,com_heji,
    	laowufei,canbaojin,danganfei,paysum_zhongqi,sal_add_json,sal_del_json,sal_free_json
    	) values('{$salary['employid']}',{$salary['salaryTimeId']},{$salary['per_yingfaheji']},
    	{$salary['per_shiye']},{$salary['per_yiliao']},{$salary['per_yanglao']},{$salary['per_gongjijin']},
    	{$salary['per_daikoushui']},{$salary['per_koukuangheji']},{$salary['per_shifaheji']},{$salary['com_shiye']},{$salary['com_yiliao']},
    	{$salary['com_yanglao']},{$salary['com_gongshang']},{$salary['com_shengyu']},{$salary['com_gongjijin']},
    	{$salary['com_heji']},{$salary['laowufei']},{$salary['canbaojin']},{$salary['danganfei']},{$salary['paysum_zhongqi']},'{$salary['sal_add_json']}','{$salary['sal_del_json']}','{$salary['sal_free_json']}')";
        $list = $this->g_db_query ( $sql );
        if ($list) {
            return $this->g_db_last_insert_id ();
        } else {
            return false;
        }
    }
    // 保存年终奖工资
    function saveNianSalary($salary) {
        $sql = "insert  into  OA_nian_salary (employid,salaryTimeId,nianzhongjiang,nian_daikoushui,yingfaheji,shifajinka,jiaozhongqi)
    	values('{$salary['employid']}',{$salary['salaryTimeId']},{$salary['nianzhongjiang']},
    	{$salary['nian_daikoushui']},{$salary['yingfaheji']},{$salary['shifajinka']},{$salary['jiaozhongqi']});";
        $list = $this->g_db_query ( $sql );
        if ($list) {
            return $this->g_db_last_insert_id ();
        } else {
            return false;
        }
    }
    // 保存二次工资
    function saveErSalary($salary) {
        $sql = "insert  into  OA_er_salary (employid,salaryTimeId,dangyueyingfa,ercigongziheji,yingfaheji,shiye,yiliao,yanglao
    	,gongjijin,yingkoushui,yikoushui,bukoushui,jinka,jiaozhongqi)
    	values('{$salary['employid']}',{$salary['salaryTimeId']},{$salary['dangyueyingfa']},
    	{$salary['ercigongziheji']},{$salary['yingfaheji']},{$salary['shiye']},{$salary['yiliao']},
    	{$salary['yanglao']},{$salary['gongjijin']},{$salary['yingkoushui']},{$salary['yikoushui']},
    	{$salary['bukoushui']},{$salary['jinka']},{$salary['jiaozhongqi']});";
        $list = $this->g_db_query ( $sql );
        if ($list) {
            return $this->g_db_last_insert_id ();
        } else {
            return false;
        }
    }
    function getErSalaryByDateNo($date, $eno) {
        $sql = " select  *  from  OA_er_salary  ,OA_salarytime_other  where OA_salarytime_other.id=OA_er_salary.salaryTimeId and  OA_salarytime_other.salaryTime='" . $date . "' and OA_er_salary.employid='" . $eno . "' ";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    // 存储工资动态字段
    function saveSalaryMovement($salaryMovement) {
        /**
         * OA_salarymovement` (
         * `id` int(11) NOT NULL AUTO_INCREMENT,
         * `fieldName` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
         * `fieldIndex` int(11) DEFAULT NULL,
         * `fieldValue` float(10,0) NOT NULL,
         * `salaryId` int(11) NOT NULL,
         * PRIMARY KEY (`id`)
         *
         * @var unknown_type
         */
        $sql = "insert into OA_salarymovement  (fieldName,salaryId,fieldValue)  values('{$salaryMovement['fieldName']}',{$salaryMovement['salaryId']},'{$salaryMovement['fieldValue']}');";
        $list = $this->g_db_query ( $sql );
        if ($list) {
            return $this->g_db_last_insert_id ();
        } else {
            return false;
        }
    }
    // 存储二次工资动态字段
    function saveErSalaryMovement($salaryMovement) {
        /**
         * o`id` int(11) NOT NULL AUTO_INCREMENT,
         * `fieldName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
         * `fieldIndex` int(11) DEFAULT NULL,
         * `fieldValue` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
         * `ersalaryId` int(11) DEFAULT NULL,
         *
         * @var unknown_type
         */
        $sql = "insert into OA_er_movement  (fieldName,ersalaryId,fieldValue)  values('{$salaryMovement['fieldName']}',{$salaryMovement['ersalaryId']},'{$salaryMovement['fieldValue']}');";
        $list = $this->g_db_query ( $sql );
        if ($list) {
            return $this->g_db_last_insert_id ();
        } else {
            return false;
        }
    }
    // 查询员工个人工资根据员工相应字段BY孙瑞鹏
    function searchSalaryListBy_Salary($id) {
        $sql = "select st.*,st.id  as stId,e.* ,e.id as  eId,s.*,s.id as sId  from OA_salarytime st,OA_employ e,OA_salary s
		where  st.id=s.salaryTimeId  and e.e_num=s.employid and s.employid='{$id}' ";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function updateSumSalary($salary) {
        $sql = "update OA_total
        set sum_per_yingfaheji = {$salary['per_yingfaheji']},
            sum_per_shiye = {$salary['per_shiye']},
            sum_per_yiliao = {$salary['per_yiliao']},
            sum_per_yanglao = {$salary['per_yanglao']},
            sum_per_gongjijin = {$salary['per_gongjijin']},
            sum_per_daikoushui = {$salary['per_daikoushui']},
            sum_per_koukuangheji = {$salary['per_koukuangheji']},
            sum_per_shifaheji = {$salary['per_shifaheji']},
            sum_com_shiye = {$salary['com_shiye']},
            sum_com_yiliao= {$salary['com_yiliao']},
            sum_com_yanglao = {$salary['com_yanglao']},
            sum_com_gongshang = {$salary['com_gongshang']},
            sum_com_shengyu = {$salary['com_shengyu']},
            sum_com_gongjijin = {$salary['com_gongjijin']},
            sum_com_heji = {$salary['com_heji']},
            sum_laowufei = {$salary['laowufei']},
            sum_canbaojin = {$salary['canbaojin']},
            sum_danganfei = {$salary['danganfei']},
            sum_paysum_zhongqi = {$salary['paysum_zhongqi']}
    	where salaryTime_Id = {$salary['salaryTimeId']}
    	;";
        $result = $this->g_db_query ( $sql );

        return $result;
    }
    // 保存工资合计项
    function saveSumSalary($salary) {
        $sql = "insert  into  OA_total (salaryTime_Id,sum_per_yingfaheji,sum_per_shiye,sum_per_yiliao,sum_per_yanglao,sum_per_gongjijin,sum_per_daikoushui
    	,sum_per_koukuangheji,sum_per_shifaheji,sum_com_shiye,sum_com_yiliao,sum_com_yanglao,sum_com_gongshang,sum_com_shengyu,sum_com_gongjijin,sum_com_heji,
    	sum_laowufei,sum_canbaojin,sum_danganfei,sum_paysum_zhongqi
    	) values({$salary['salaryTimeId']},{$salary['per_yingfaheji']},
    	{$salary['per_shiye']},{$salary['per_yiliao']},{$salary['per_yanglao']},{$salary['per_gongjijin']},
    	{$salary['per_daikoushui']},{$salary['per_koukuangheji']},{$salary['per_shifaheji']},{$salary['com_shiye']},{$salary['com_yiliao']},
    	{$salary['com_yanglao']},{$salary['com_gongshang']},{$salary['com_shengyu']},{$salary['com_gongjijin']},
    	{$salary['com_heji']},{$salary['laowufei']},{$salary['canbaojin']},{$salary['danganfei']},{$salary['paysum_zhongqi']});";
        $list = $this->g_db_query ( $sql );
        if ($list) {
            return $this->g_db_last_insert_id ();
        } else {
            return false;
        }
    }
    // 保存年终奖合计项
    function saveSumNianSalary($salary) {
        $sql = "insert  into  OA_nian_total (salaryTime_Id,sum_nianzhongjiang,sum_daikoushui,sum_yingfaheji,sum_shifajika,sum_jiaozhongqi)
        values({$salary['salaryTimeId']},{$salary['nianzhongjiang']},{$salary['yingfaheji']},{$salary['nian_daikoushui']},{$salary['shifajinka']},{$salary['jiaozhongqi']});";
        $list = $this->g_db_query ( $sql );
        if ($list) {
            return $this->g_db_last_insert_id ();
        } else {
            return false;
        }
    }
    // 保存二次工资合计项
    function saveSumErSalary($salary) {
        $sql = "insert  into  OA_er_total (salaryTime_Id,sum_dangyueyingfa,sum_ercigongziheji,sum_yingfaheji,sum_shiye
        ,sum_yiliao,sum_yanglao,sum_gongjijin,sum_yingkoushui,sum_yikoushui,sum_bukoushui,sum_jinka,sum_jiaozhongqi)
        values({$salary['salaryTimeId']},{$salary['dangyueyingfa']},{$salary['ercigongziheji']},{$salary['yingfaheji']},
        {$salary['shiye']},{$salary['yiliao']},{$salary['yanglao']},{$salary['gongjijin']},{$salary['yingkoushui']},
        {$salary['yikoushui']},{$salary['bukoushui']},{$salary['jinka']},{$salary['jiaozhongqi']});";
        $list = $this->g_db_query ( $sql );
        if ($list) {
            return $this->g_db_last_insert_id ();
        } else {
            return false;
        }
    }
    function searhSalaryTimeListByComIdAndDate($date, $comid) {
        $sql = "select * from OA_salarytime  where  salaryTime like'%{$date}%' and companyId=$comid ";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    function searhNianSalaryTimeListByComIdAndDate($date, $comid) {
        $sql = "select * from OA_salarytime_other  where  salaryTime like'%{$date}%' and companyId=$comid and  salaryType = 5";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    function searhSalaryTimeList($where = null) {
        $sql = "select st.*,c.company_name from OA_salarytime st,OA_company c where  st.companyId=c.id  ";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and c.company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and st.salaryTime='{$where['salaryTime']}' ";
            }
            if ($where ['op_salaryTime'] != "") {
                $sql .= " and st.op_salaryTime='{$where['op_salaryTime']}' ";
            }
        }
        $sql .= " order by op_salaryTime desc ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }

    // 发票统计数BY孙瑞鹏
    function searhFapiaoCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select count(*) as cnt  from OA_bill b ,OA_salarytime  s ,OA_company c ,OA_admin_company a
                    WHERE  b.salaryTime_id = s.id
                    AND s.companyId=c.id
                    AND a.companyId = c.id
                    AND bill_type = 1
                    AND a.adminId = $id";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and salaryTime='{$where['salaryTime']}' ";
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }

    // 到账统计数BY孙瑞鹏
    function searhDaozhangCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select count(*) as cnt  from OA_bill b ,OA_salarytime  s ,OA_company c ,OA_admin_company a
                    WHERE  b.salaryTime_id = s.id
                    AND s.companyId=c.id
                    AND a.companyId = c.id
                    AND bill_type = 3
                    AND a.adminId = $id";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and salaryTime='{$where['salaryTime']}' ";
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }

    // 个税统计BY孙瑞鹏
    function searhSalaryTimeCount() {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select c.id,c.company_name from OA_company c,OA_admin_company a  where 1=1
  and a.companyId = c.id  and  a.adminId = $id";
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }

    // 个税类型BY孙瑞鹏
    function searhSalaryTypeCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select count(*) as cnt   from OA_company c,OA_admin_company a  where 1=1
  and a.companyId = c.id  and  a.adminId = $id";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and geshui_dateType='{$where['salaryTime']}' ";
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }
    // 公司级别统计数BY孙瑞鹏
    function searhGongsijibieCount($where = null) {
        $sql = "select count(*) as cnt   from OA_company   where 1=1
  and company_level=0 ";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
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
     * 工资查询 dao
     * @param null $where
     * @return int
     */
    function searhSalaryTimeListCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select count(*) as cnt  from OA_salarytime st,OA_company c,OA_admin_company a where  a.adminId=$id and a.companyId = c.id and st.companyId=c.id  ";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and c.company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['companyId'] != "") {
                $sql .= " and c.id  = {$where['companyId']}  ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and st.salaryTime like '%{$where['salaryTime']}%' ";
            }
            if ($where ['op_salaryTime'] != "") {
                $sql .= " and st.op_salaryTime>='{$where['op_time']}' and st.op_salaryTime<'{$where['op_salaryTime']}' ";
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }
    function searhSalaryTimeListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select st.*,c.company_name from OA_salarytime st,OA_company c,OA_admin_company a where a.adminId=$id  and st.companyId=c.id  ";
        if ($id && $_SESSION ['admin'] ['searchOrder']) {
            $sql.= ' and a.companyId = c.id';
        }
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and c.company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and st.salaryTime  like '%{$where['salaryTime']}%' ";
            }
            if ($where ['op_salaryTime'] != "") {
                $sql .= " and st.op_salaryTime>='{$where['op_time']}' and st.op_salaryTime<'{$where['op_salaryTime']}'";
            }
        }
        if ($sort) {
            $sql .= " order by $sort";
        }
        if(null==$where['e_sal_approve']&&null==$where['e_fa_state']){
            if ($start >= 0 && $limit) {
                $sql .= " limit $start,$limit";
            }
        }
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    //查询带条件的公司BY孙瑞鹏
    function searchCompanyListByName($where=null,$type=null) {
        $id = $_SESSION ['admin'] ['id'];
        $time = date('Y',strtotime("-1 year"));
        $sql = "select c.id,c.company_name from OA_company c,OA_admin_company a  where
   a.companyId = c.id  and  a.adminId = $id";
        if ($where) {
            $sql .= " and c.company_name  like '%{$where}%' ";
        }
        if ($type == 1) {
            $sql .= " AND c.id   in (SELECT comId FROM OA_gesui  WHERE salTime LIKE '%{$time}%' AND geshui_state = 1  and geSui_type = 2) ";
        }
        if ($type == 2) {
            $sql .= " AND c.id  NOT in (SELECT comId FROM OA_gesui  WHERE salTime LIKE '%{$time}%' AND geshui_state = 1  and geSui_type = 2) ";
        }
        $result = $this->g_db_query ( $sql );

        return $result;
    }

    //查询残疾人带条件的公司BY孙瑞鹏
    function searchCompanyListByCanjiren($where=null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT b.id,b.company_name,COUNT(*) sumcanjiren FROM
(select c.id,c.company_name from OA_company c,OA_admin_company a  where
   a.companyId = c.id  and  a.adminId = $id) b,
(SELECT * FROM OA_employ WHERE e_teshu_state = 1) emp
WHERE convert( emp.e_company  using utf8)  = b.company_name

";
        if ($where != ""&&$where!= null) {
            $sql .= " and b.company_name like '%{$where}%' ";
        }
        $sql.="GROUP BY emp.e_company";
        $result = $this->g_db_query ( $sql );

        return $result;
    }
    // 计算个税合计BY孙瑞鹏
    function searhGeshuiListPage($where = null,$id = null) {

        $time = date ( "Y-m", strtotime ( "last month", strtotime ( $where ['salaryTime'] ) ) );
        $sql = "SELECT yi.companyId company_id,yi.salaryTime,yi.company_name company_name,yi.su daikou,er.su bukou, (IFNULL(yi.su,0)+IFNULL(er.su,0))  geshuiSum FROM
			(
     SELECT t.companyId,  t.salaryTime  ,c.company_name,SUM(s.per_daikoushui) su
    FROM OA_salary s ,OA_employ emp,OA_salarytime t,OA_company c
			WHERE s.employid = emp.e_num AND s.salaryTimeId = t.id AND t.companyId = c.id
			AND  c.geshui_dateType <> 3
       AND (
    			(t.salaryTime like '%{$where ['salaryTime']}%' AND c.geshui_dateType =1 )
    			OR
    			(t.salaryTime like '%{$time}%' AND c.geshui_dateType =2  )
    			)
      AND t.companyId = $id
      GROUP BY t.companyId,t.salaryTime
      ) yi
			LEFT JOIN
			 (
     SELECT  t.companyId,t.salaryTime,e_company, SUM(e.bukoushui) su
     FROM
      OA_er_salary e ,OA_employ emp,OA_salarytime_other t
			WHERE e.employid = emp.e_num  AND e.salaryTimeId = t.id
			GROUP BY t.companyId,t.salaryTime
			) er
			ON yi.companyId = er.companyId AND yi.salaryTime = er.salaryTime
    		where 1=1
";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and yi.company_name like '%{$where['companyName']}%' ";
            }

        }

        // $sql.=" order by op_salaryTime desc ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }

    // 发票BY孙瑞鹏
    function searhFapiaoListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT  bill_no,salaryTime ,company_name ,bill_value FROM OA_bill b ,OA_salarytime  s ,OA_company c ,OA_admin_company a
                    WHERE  b.salaryTime_id = s.id
                    AND s.companyId=c.id
                    AND a.companyId = c.id
                    AND bill_type = 1
                    AND a.adminId = $id";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= "  and salaryTime like '%{$where['salaryTime']}%' ";
            }

        }
        if ($sort) {
            $sql .= " order by $sort";
        }
        if ($start >= 0 && $limit) {
            $sql .= " limit $start,$limit";
        }
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 增减员删除BY孙瑞鹏
    function deleteZengjian($id) {
        $sql = "delete  FROM OA_security WHERE id=$id";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 增减员BY孙瑞鹏
    function searhZengjianListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $sql = "SELECT * FROM OA_security WHERE 1=1";
        if ($where != null) {
            if ($where ['search_type'] != "") {
                $sql .= " and business_type = '{$where['search_type']}' ";
            }
            if ($where ['companyName'] != "") {
                $sql .= " and Dept like '%{$where['companyName']}%' ";
            }
            if ($where ['shenbaozhuangtai'] != "") {
                $sql .= " and Dept like '%{$where['companyName']}%' ";
            }
            if ($where ['shenbaozhuangtai'] != "") {
                $sql .= " and shenbaozhuangtai = '{$where['shenbaozhuangtai']}' ";
            }
            if ($where ['ename'] != "") {
                $sql .= " and EName like '%{$where['ename']}%' ";
            }
            if ($where ['zengjian'] != "") {
                $sql .= "  and zengjianbiaozhi like '%{$where['zengjian']}%' ";
            }
            if ($where ['first'] != "") {
                $sql .= "  and submitTime >= '{$where['first']}' and submitTime <=  '{$where['last']}'  ";
            }
        }
        if ($sort) {
            $sql .= " order by $sort";
        }
        if ($start >= 0 && $limit) {
            $sql .= " limit $start,$limit";
        }
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 增减员统计BY孙瑞鹏
    function searhZengjianTongjiPage($where = null) {
        $sql = "SELECT count(id) as cnt FROM OA_security WHERE 1=1";
        if ($where != null) {
            if ($where ['search_type'] != "") {
                $sql .= " and business_type = '{$where['search_type']}' ";
            }
            if ($where ['companyName'] != "") {
                $sql .= " and Dept like '%{$where['companyName']}%' ";
            }
            if ($where ['shenbaozhuangtai'] != "") {
                $sql .= " and Dept like '%{$where['companyName']}%' ";
            }
            if ($where ['shenbaozhuangtai'] != "") {
                $sql .= " and shenbaozhuangtai = '{$where['shenbaozhuangtai']}' ";
            }
            if ($where ['ename'] != "") {
                $sql .= " and EName like '%{$where['ename']}%' ";
            }
            if ($where ['zengjian'] != "") {
                $sql .= "  and zengjianbiaozhi like '%{$where['zengjian']}%' ";
            }
            if ($where ['first'] != "") {
                $sql .= "  and submitTime >{$where['first']} and submitTime < {$where['last']}";
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }
    // 到账BY孙瑞鹏
    function searhDaozhangListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT  salaryTime daozhangTime ,company_name cname ,bill_value  daozhangValue FROM OA_bill b ,OA_salarytime  s ,OA_company c ,OA_admin_company a
                    WHERE  b.salaryTime_id = s.id
                    AND s.companyId=c.id
                    AND a.companyId = c.id
                    AND bill_type = 3
                    AND a.adminId = $id";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= "  and salaryTime like '%{$where['salaryTime']}%' ";
            }

        }
        if ($sort) {
            $sql .= " order by $sort";
        }
        if ($start >= 0 && $limit) {
            $sql .= " limit $start,$limit";
        }
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 残疾人详细BY孙瑞鹏
    function searhCanjirenXiangxi($cid) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT emp.id,e_name,e_company,e_num, e_teshu_state FROM OA_employ emp,
(SELECT c.company_name  from OA_company c,OA_admin_company a
 WHERE a.companyId = c.id  and  a.adminId = $id  AND c.id= $cid) b
WHERE convert( emp.e_company  using utf8) = b.company_name
and emp.e_teshu_state=1
";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 残疾人链表BY孙瑞鹏
    function searhCanjireniTypePage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT emp.id,e_name,e_company,e_num, e_teshu_state FROM OA_employ emp,
(SELECT c.company_name  from OA_company c,OA_admin_company a
 WHERE a.companyId = c.id  and  a.adminId = $id ) b
WHERE convert( emp.e_company  using utf8) = b.company_name";
        if ($where ['ename'] != "") {
            $sql .= " and emp.e_name like '%{$where['ename']}%' ";
        }
        if ($where ['empnum'] != "") {
            $sql .= " and emp.e_num ='{$where['empnum']}' ";
        }

//        if ($sort) {
//            $sql .= " order by $sort";
//        }
//        if ($start >= 0 && $limit) {
//            $sql .= " limit $start,$limit";
//        }
        // $sql.=" order by op_salaryTime desc ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 个税类型BY孙瑞鹏
    function searhGeshuiTypePage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT c.id,c.company_name,c.geshui_dateType  from OA_company c,OA_admin_company a  where 1=1
  and a.companyId = c.id  and  a.adminId = $id";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and geshui_dateType='{$where['salaryTime']}' ";
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
    // 公司级别BY孙瑞鹏
    function searhGongsijibiePage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $sql = "SELECT c.id,c.company_name,c.company_level,COUNT(d.company_name) geshu
FROM OA_company  c  LEFT JOIN OA_company d
ON c.id = d.company_level where  1 = 1
";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and c.company_name like '%{$where['companyName']}%' ";
            }
        }
        $sql .= " GROUP BY c.id ";
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



    // 二级公司BY孙瑞鹏
    function searhErjigongsi($superId = NULL) {
        $sql = "SELECT id,company_name,company_level FROM OA_company  where  company_level =$superId ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function searhErSalaryTimeListCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select count(*) as cnt  from OA_salarytime_other st,OA_company c,OA_admin_company a where  a.adminId=$id and a.companyId = c.id and st.companyId=c.id   and salaryType=" . ER_SALARY_TIME_TYPE;
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and c.company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and st.salaryTime like '%{$where['salaryTime']}%' ";
            }
            if ($where ['op_salaryTime'] != "") {
                $sql .= " and st.op_salaryTime='{$where['op_salaryTime']}' ";
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }
    function searhErSalaryTimeListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select st.*,c.company_name from OA_salarytime_other st,OA_company c,OA_admin_company a where  a.adminId=$id and a.companyId = c.id and st.companyId=c.id  and salaryType=" . ER_SALARY_TIME_TYPE;
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and c.company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and st.salaryTime   like '%{$where['salaryTime']}%'  ";
            }
            if ($where ['op_salaryTime'] != "") {
                $sql .= " and st.op_salaryTime='{$where['op_salaryTime']}' ";
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
    // 个税详细BY孙瑞鹏
    function searchGeshuiBy_SalaryTimeId($sid, $stime,$nian) {
        $time = date('Y',strtotime("-1 year"));
        if ($nian==1) {
            $sql = "SELECT yi.id company_id,yi.e_name ename ,e_num,yi.salaryTime,yi.e_company companyname,yi.su daikou,er.su bukou,nian.su nian,(yi.su+IFNULL(er.su,0)+IFNULL(nian.su,0)) geshuiSum FROM
    	(
    	SELECT emp.id,e_num,emp.e_name,  t.salaryTime  ,e_company,  IFNULL(SUM(s.per_daikoushui),0) su
    	FROM OA_salary s ,OA_employ emp,OA_salarytime t
    	WHERE s.employid = emp.e_num AND s.salaryTimeId = t.id
       AND t.companyId = '$sid'
    	AND t.salaryTime = '$stime'
    	GROUP BY s.employid,t.salaryTime,emp.id
    	ORDER BY e_name
    	) yi
    	LEFT JOIN
    	(
    	SELECT emp.e_name,e.employid, t.salaryTime,e_company, IFNULL(SUM(e.bukoushui),0) su
    	FROM OA_er_salary e ,OA_employ emp,OA_salarytime_other t
    	WHERE e.employid = emp.e_num  AND e.salaryTimeId = t.id
    	AND t.companyId = '$sid'
    	AND t.salaryTime = '$stime'
    	GROUP BY e.employid,t.salaryTime,emp.id
    	) er
    	ON yi.e_name = er.e_name AND yi.salaryTime = er.salaryTime AND yi.e_num = er.employid
    	LEFT JOIN
    	(
    	SELECT  emp.e_name,   n.employid, t.salaryTime,e_company, IFNULL(SUM(n.nian_daikoushui),0) su
    	FROM OA_nian_salary n  ,OA_employ emp,OA_salarytime_other t
    	WHERE n.employid = emp.e_num AND n.salaryTimeId = t.id
    	AND t.companyId = '$sid'
    	AND t.salaryTime like '%{$time}%'
    	GROUP BY n.employid,emp.id
    	) nian
    	ON  yi.e_name = nian.e_name AND yi.e_num = nian.employid
    	where 1=1";
        }elseif($nian == 0){
            $sql = "SELECT yi.id company_id,yi.e_name ename ,e_num,yi.salaryTime,yi.e_company companyname,yi.su daikou,er.su bukou,(yi.su+IFNULL(er.su,0)) geshuiSum FROM
    	(
    	SELECT emp.id,e_num,emp.e_name,  t.salaryTime  ,e_company,  IFNULL(SUM(s.per_daikoushui),0) su
    	FROM OA_salary s ,OA_employ emp,OA_salarytime t
    	WHERE s.employid = emp.e_num AND s.salaryTimeId = t.id
    	AND t.companyId = '$sid'
    	AND t.salaryTime = '$stime'
    	GROUP BY s.employid,t.salaryTime,emp.id
    	ORDER BY e_name
    	) yi
    	LEFT JOIN
    	(
    	SELECT emp.e_name,e.employid, t.salaryTime,e_company,  IFNULL(SUM(e.bukoushui),0) su
    	FROM OA_er_salary e ,OA_employ emp,OA_salarytime_other t
    	WHERE e.employid = emp.e_num  AND e.salaryTimeId = t.id
    	AND t.companyId = '$sid'
    	AND t.salaryTime = '$stime'
    	GROUP BY e.employid,t.salaryTime,emp.id
    	) er
    	ON yi.e_name = er.e_name AND yi.salaryTime = er.salaryTime AND yi.e_num = er.employid
    	where 1=1";

        }

        $list = $this->g_db_query ( $sql );
        return $list;
    }

    // 插入个税标识BY孙瑞鹏
    function insertGeshui($salaryTimeId, $salaryTime,$type) {
        $sql = "insert into OA_gesui (salaryTimeId,salTime,geSui_type,comId,geshui_state)
                   values ((SELECT id FROM OA_salarytime WHERE companyId = '$salaryTimeId' AND salaryTime = '$salaryTime'),'$salaryTime','$type', '$salaryTimeId' ,1)";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 插入年终奖个税标识BY孙瑞鹏
    function insertNianGeshui($salaryTimeId, $salaryTime,$type) {
        $sql = "insert into OA_gesui (salaryTimeId,salTime,geSui_type,comId,geshui_state)
                   values ((SELECT id FROM OA_salarytime_other WHERE companyId = '$salaryTimeId' AND salaryTime  like '%{$salaryTime}%' and  salaryType = 5),(SELECT salaryTime FROM OA_salarytime_other WHERE companyId = '$salaryTimeId' AND salaryTime  like '%{$salaryTime}%' and  salaryType = 5),'$type', '$salaryTimeId' ,1)";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 判断个税标识是否存在BY孙瑞鹏
    function isGeshui($salaryTimeId, $salaryTime,$type) {
        $sql = "SELECT id FROM OA_gesui WHERE comId = '$salaryTimeId' AND salTime  like '%{$salaryTime}%' and geSui_type = '$type'";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 判断年个税标识是否存在BY孙瑞鹏
    function isNianGeshui($salaryTimeId, $salaryTime) {
        $sql = "SELECT id FROM OA_salarytime_other WHERE companyId = '$salaryTimeId' AND salaryTime  like '%{$salaryTime}%' and  salaryType = 5";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 判断单月个税标识是否存在BY孙瑞鹏
    function isYueGeshui($salaryTimeId, $salaryTime) {
        $sql = "SELECT id FROM OA_salarytime WHERE companyId = '$salaryTimeId' AND salaryTime = '$salaryTime' ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 个税类型设置BY孙瑞鹏
    function searchGeshuiBy_SalaryTypeId($sid) {
        $sql = "SELECT geshui_dateType ,company_name FROM OA_company WHERE id=$sid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 增员BY孙瑞鹏
    function setZengyuan($type,$CName,$Dept,$EName,$EmpNo,$EmpType,$shebaojishu,$waiquzhuanru,$sum1,$danweijishu,$caozuoren,$shenbaozhuangtai,$beizhu,$zengjianbiaozhi,$tel,$gongjijin,$gongjijinsum) {
        $sql = "insert into OA_security (submitTime,CName,Dept,EName,EmpNo,EmpType,shebaojishu,waiquzhuanru,sum,danweijishu,caozuoren,shenbaozhuangtai,beizhu,zengjianbiaozhi,tel,gongjijinjishu,gongjijinsum,business_type)
                   values (now(),'{$CName}','{$Dept}','{$EName}','{$EmpNo}','{$EmpType}','{$shebaojishu}','{$waiquzhuanru}','{$sum1}','{$danweijishu}','{$caozuoren}','{$shenbaozhuangtai}','{$beizhu}','{$zengjianbiaozhi}','{$tel}','{$gongjijin}','{$gongjijinsum}','{$type}')";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    //所有父公司
    function searchCompanyListSuper($start = NULL, $limit = NULL, $sort = NULL, $where = '1=1') {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select c.id,c.company_name from OA_company c  where $where and company_level=0";
        if ($sort) {
            $sql .= " order by $sort";
        }
        if ($start >= 0 && $limit) {
            $sql .= " limit $start,$limit";
        }
        // echo $sql;
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    //所有父公司数量
    function g_db_countSuper($table,$key,$where,$db=null){
        if (!$table) {
            return 0;
        }
        if (!$key) {
            $key = '*';
        }
        $query = "select count({$key}) as cnt from {$table} where {$where} and  company_level=0";
        $result = $this->g_db_query($query,$db);
        if (!$result) {
            return 0;
        }
        $row = mysql_fetch_assoc($result);
        return $row['cnt'];
    }
    // 离职设置BY孙瑞鹏
    function setTypeLizhi($sid) {
        $sql = "UPDATE OA_employ SET e_state =1 where e_num = '{$sid}' ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 个税类型设置BY孙瑞鹏
    function setTypeGeshui($sid,$type) {
        $sql = "UPDATE OA_company SET geshui_dateType = $type WHERE id = $sid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 公司级别设置BY孙瑞鹏
    function setTypeGongsijibie($sid=null,$type=null) {
        $sql = "UPDATE OA_company SET company_level = $type WHERE id = $sid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 公司级别添加验证BY孙瑞鹏
    function selectGongsijibie($type=null) {
        $sql = "select id from OA_company  WHERE company_level = $type";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 残疾人设置BY孙瑞鹏
    function setTypeCanjiren($sid,$type) {
        $sql = "UPDATE OA_employ SET e_teshu_state = $type WHERE id = $sid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 获得所有年终奖
    function searhSalaryNianTimeList($where = null) {
        $sql = "select st.*,c.company_name from OA_salarytime_other st,OA_company c where  st.companyId=c.id  and salaryType=" . SALARY_TIME_TYPE;
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and c.company_name='{$where['companyName']}' ";
            } elseif ($where ['salaryTime'] != "") {
                $sql .= " and st.salaryTime='{$where['salaryTime']}' ";
            } elseif ($where ['op_salaryTime'] != "") {
                $sql .= " and st.op_salaryTime='{$where['op_salaryTime']}' ";
            }
        }
        $sql .= " order by op_salaryTime desc ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }

    // 年终奖计算分页功能
    function searhSalaryNianTimeListCount($where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select count(*) as cnt   from OA_salarytime_other st,OA_company c ,OA_admin_company a where  st.companyId=c.id and a.companyId = c.id  and  a.adminId = $id and salaryType=" . SALARY_TIME_TYPE;
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and c.company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and st.salaryTime like '%{$where['salaryTime']}%'";
            }
            if ($where ['op_salaryTime'] != "") {
                $sql .= " and st.op_salaryTime>='{$where['op_time']}' and st.op_salaryTime<'{$where['op_salaryTime']}' ";
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }
    function searhSalaryNianTimeListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select st.*,c.company_name from OA_salarytime_other st,OA_company c  ,OA_admin_company a where  st.companyId=c.id and a.companyId = c.id  and  a.adminId = $id and salaryType=" . SALARY_TIME_TYPE;
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and c.company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and st.salaryTime like '%{$where['salaryTime']}%'  ";
            }
            if ($where ['op_salaryTime'] != "") {
                $sql .= " and st.op_salaryTime>='{$where['op_time']}' and st.op_salaryTime<'{$where['op_salaryTime']}' ";
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
    // *** 年终奖计算以上结束
    function searhSalaryErTimeList($where = null) {
        $sql = "select st.*,c.company_name from OA_salarytime_other st,OA_company c where  st.companyId=c.id  and salaryType=" . ER_SALARY_TIME_TYPE;
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and c.company_name='{$where['companyName']}' ";
            } elseif ($where ['salaryTime'] != "") {
                $sql .= " and st.salaryTime='{$where['salaryTime']}' ";
            } elseif ($where ['op_salaryTime'] != "") {
                $sql .= " and st.op_salaryTime='{$where['op_salaryTime']}' ";
            }
        }
        $sql .= " order by op_salaryTime desc ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function searchSalaryListBy_SalaryTimeId($sid,$count = null) {
        $sql = "select oe.e_name ,os.*  from OA_salary os,OA_employ oe where os.employId = oe.e_num and  salaryTimeId=$sid";
        if ($count) {
            $sql .= " limit $count";
        }
        $list = $this->g_db_query ( $sql );
        return $list;
    }

    function searchSalaryListBy_SalaryTimeId_New($sid) {
        $sql = "SELECT c.*,b.* FROM (select *  from OA_salary where salaryTimeId=$sid)  b,
(select salaryId,
                MAX(CASE WHEN a.fieldName = '部门' THEN a.fieldValue ELSE NULL END)  as 部门,
                MAX(CASE WHEN a.fieldName = '姓名' THEN a.fieldValue ELSE NULL END)  as 姓名,
								MAX(CASE WHEN a.fieldName = '身份证号' THEN a.fieldValue ELSE NULL END)  as 身份证号,
								MAX(CASE WHEN a.fieldName = '基本工资' THEN a.fieldValue ELSE NULL END)  as 基本工资,
								MAX(CASE WHEN a.fieldName = '职务工资' THEN a.fieldValue ELSE NULL END)  as 职务工资,
								MAX(CASE WHEN a.fieldName = '年度骨干津贴' THEN a.fieldValue ELSE NULL END)  as 年度骨干津贴,
								MAX(CASE WHEN a.fieldName = '季度骨干津贴' THEN a.fieldValue ELSE NULL END)  as 季度骨干津贴,
								MAX(CASE WHEN a.fieldName = '月骨干津贴' THEN a.fieldValue ELSE NULL END)  as 月骨干津贴,
								MAX(CASE WHEN a.fieldName = '保密津贴' THEN a.fieldValue ELSE NULL END)  as 保密津贴,
								MAX(CASE WHEN a.fieldName = '补发工资' THEN a.fieldValue ELSE NULL END)  as 补发工资,
								MAX(CASE WHEN a.fieldName = '交通补贴' THEN a.fieldValue ELSE NULL END)  as 交通补贴,
								MAX(CASE WHEN a.fieldName = '季度奖' THEN a.fieldValue ELSE NULL END)  as 季度奖,
								MAX(CASE WHEN a.fieldName = '质量奖' THEN a.fieldValue ELSE NULL END)  as 质量奖,
								MAX(CASE WHEN a.fieldName = '考核工资' THEN a.fieldValue ELSE NULL END)  as 考核工资,
								MAX(CASE WHEN a.fieldName like '%银行卡号%'   THEN a.fieldValue ELSE NULL END)  as 银行卡号,
								MAX(CASE WHEN a.fieldName = '身份类别' THEN a.fieldValue ELSE NULL END)  as 身份类别,
								MAX(CASE WHEN a.fieldName  like '%社保基数%'  THEN a.fieldValue ELSE NULL END)  as 社保基数,
								MAX(CASE WHEN a.fieldName   like '%公积金基数%' THEN a.fieldValue ELSE NULL END)  as 公积金基数
                FROM OA_salarymovement as a
                WHERE salaryId in  (select id  from OA_salary where salaryTimeId=$sid)   group by salaryId
) c  WHERE b.id = c.salaryId";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 查询年终奖
    function searchNianSalaryListBy_SalaryTimeId($sid) {
        $sql = "select *  from OA_nian_salary where salaryTimeId=$sid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 查询个人年终奖
    function searchNianSalaryListBy_SalaryTimeIdAndPersonNo($sid, $employNo) {
        $sql = "select *  from OA_nian_salary where salaryTimeId=$sid and employid='{$employNo}'";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function searchNianSalaryTimeBySalaryTimeAndComId($sTime, $companyId) {
        $sql = "select *  from OA_salarytime_other where salaryTime='$sTime' and salaryType=" . SALARY_TIME_TYPE . " and companyId=$companyId ";
        $list = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $list );
    }
    function searchErSalaryTimeBySalaryTimeAndComId($sTime, $companyId) {
        $sql = "select *  from OA_salarytime_other where salaryTime='$sTime' and salaryType=" . ER_SALARY_TIME_TYPE . " and companyId=$companyId ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function searchNianSalaryAndErSalaryTimeByComId($companyId) {
        $sql = "select *  from OA_salarytime_other where   companyId=$companyId ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 查询er
    function searchErSalaryListBy_SalaryTimeId($sid) {
        $sql = "select *  from OA_er_salary where salaryTimeId=$sid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function searchErSalaryListBy_SalaryTimeIdAndPersonId($sid, $personId) {
        $sql = "select *  from OA_er_salary where salaryTimeId=$sid  and employid='$personId'";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    // 查询员工二次工资合计项
    function searchErSalHejiByPersonIdAndSalTimeErAndComId($comId, $salTime, $personId) {
        $sql = "select  sum(OA_er_salary.ercigongziheji) as erSum ,OA_er_salary.employId  from OA_salarytime_other,OA_er_salary
    	where  OA_salarytime_other.salarytime='$salTime' and OA_salarytime_other.companyId=$comId
and OA_salarytime_other.id=OA_er_salary.salarytimeId and OA_er_salary.employId='$personId' group by OA_er_salary.employId;";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    function searchErBuKouShuaiHejiByPersonIdAndSalTimeErAndComId($comId, $salTime, $personId) {
        $sql = "select  sum(OA_er_salary.bukoushui) as erSum ,OA_er_salary.employId  from OA_salarytime_other,OA_er_salary
    	where  OA_salarytime_other.salarytime='$salTime' and OA_salarytime_other.companyId=$comId
and OA_salarytime_other.id=OA_er_salary.salarytimeId and OA_er_salary.employId='$personId' group by OA_er_salary.employId;";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    // 查询员工个人工资根据员工相应字段
    function searchSalaryListBy_SalaryEmpId($emp) {
        $sql = "select st.*,st.id  as stId,e.* ,e.id as  eId,s.*,s.id as sId  from OA_salarytime st,OA_employ e,OA_salary s
    	      where  st.id=s.salaryTimeId  and e.e_num=s.employid and s.employid='{$emp['eno']}' ";
        if (! empty ( $emp ['sTime'] )) {
            $sql .= " and st.salaryTime='{$emp['sTime']}'";
        }
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    // 查询员工个人工资根据员工姓名
    function searchSalaryListBy_SalaryEmpName($emp) {
        $sql = "select st.*,st.id  as stId,e.* ,e.id as  eId,s.*,s.id as sId  from OA_salarytime st,OA_employ e,OA_salary s
    	      where  st.id=s.salaryTimeId  and e.e_num=s.employid and e.e_name='{$emp['e_name']}' ";
        if (! empty ( $emp ['sTime'] )) {
            $sql .= " and st.salaryTime='{$emp['sTime']}'";
        }
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    // 根据员工身份证号 和工资日期ID查询工资
    function searchSalaryListBy_Id($emp) {
        $sql = "select st.*,st.id as stId,e.e_name ,s.*,s.id as sId  from OA_salarytime st,OA_employ e,OA_salary s
    	      where  st.id=s.salaryTimeId  and e.e_num=s.employid and s.employid='{$emp['eno']}' and st.id={$emp['stId']}";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    // 根据工资Id修改工资
    function updateSalBy_sId($salary) {
        $sql = " update OA_salary set per_yingfaheji={$salary['per_yingfaheji']},per_shiye={$salary['per_shiye']},per_yiliao={$salary['per_yiliao']},
    	                            per_yanglao={$salary['per_yanglao']},per_gongjijin={$salary['per_gongjijin']},per_daikoushui={$salary['per_daikoushui']},
    	                            per_koukuangheji={$salary['per_koukuangheji']},per_shifaheji={$salary['per_shifaheji']},com_shiye={$salary['com_shiye']},
    	                            com_yiliao={$salary['com_yiliao']},com_yanglao={$salary['com_yanglao']},com_gongshang={$salary['com_gongshang']},
    	                            com_shengyu={$salary['com_shengyu']},com_gongjijin={$salary['com_gongjijin']},com_heji={$salary['com_heji']},
    	                            laowufei={$salary['laowufei']},canbaojin={$salary['canbaojin']},danganfei={$salary['danganfei']},paysum_zhongqi={$salary['paysum_zhongqi']}
    	                             ,salary_type=1 where id={$salary['sId']}";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    // 根据工资日期修改工资总数
    function updateTotalBySalaryTimeId($salary) {
        $sql = "update  OA_total  set
	   sum_per_yingfaheji=(select sum(per_yingfaheji) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_per_shiye=(select sum(per_shiye) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_per_yiliao=(select sum(per_yiliao) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_per_yanglao=(select sum(per_yanglao) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_per_gongjijin=(select sum(per_gongjijin) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_per_daikoushui=(select sum(per_daikoushui) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_per_koukuangheji=(select sum(per_koukuangheji) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_per_shifaheji=(select sum(per_shifaheji) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_com_shiye=(select sum(com_shiye) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_com_yiliao=(select sum(com_yiliao) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_com_yanglao=(select sum(com_yanglao) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_com_shengyu=(select sum(com_shengyu) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_com_gongjijin=(select sum(com_gongjijin) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_com_heji=(select sum(com_heji) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_laowufei=(select sum(laowufei) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_canbaojin=(select sum(canbaojin) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_danganfei=(select sum(danganfei) from OA_salary where salaryTimeId={$salary['stId']} ),
       sum_paysum_zhongqi=(select sum(paysum_zhongqi) from OA_salary where salaryTimeId={$salary['stId']} )
	   where  salaryTime_id ={$salary['stId']};";
        // echo $sql;
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function searchSumSalaryListBy_SalaryTimeId($sid) {
        $sql = "select *  from OA_total where salaryTime_Id=$sid limit 1";

        $list = $this->g_db_query ( $sql );
        return mysql_fetch_array($list);
    }
    function searchSumSalaryListBy_ManyCom($where) {
        $sql = "select sum(sum_per_yingfaheji) as sum_per_yingfaheji,
		            sum(sum_per_shiye) as sum_per_shiye,sum(sum_per_yiliao) as sum_per_yiliao
		            ,sum(sum_per_yanglao) as sum_per_yanglao,sum(sum_per_gongjijin) as sum_per_gongjijin,
		            sum(sum_per_daikoushui) as sum_per_daikoushui,sum(sum_per_koukuangheji) as sum_per_koukuangheji,
		            sum(sum_per_shifaheji) as sum_per_shifaheji,sum(sum_com_shiye) as sum_com_shiye
		            ,sum(sum_com_yiliao) as sum_com_yiliao,sum(sum_com_yanglao) as sum_com_yanglao,
		            sum(sum_com_gongshang) as sum_com_gongshang,sum(sum_com_shengyu)  as sum_com_shengyu
		            ,sum(sum_com_gongjijin)as sum_com_gongjijin,sum(sum_com_heji) as sum_com_heji,
    	sum(sum_laowufei) as sum_laowufei,sum(sum_canbaojin)  as sum_canbaojin,
    	sum(sum_danganfei)   as sum_danganfei,sum(sum_paysum_zhongqi)    as sum_paysum_zhongqi from OA_total where salaryTime_Id  in ($where)";
        $list = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $list );
    }
    // 年终奖
    function searchSumNianSalaryListBy_SalaryTimeId($sid) {
        $sql = "select *  from OA_nian_total where salaryTime_Id=$sid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    //
    function searchSumErSalaryListBy_SalaryTimeId($sid) {
        $sql = "select *  from OA_er_total where salaryTime_Id=$sid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function searchSalaryTimeBy_Salarydata($date) {
        $sql = "select *  from OA_salarytime where salaryTime='$date'";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    /**
     * 查询个税时间
     * CREATE TABLE `OA_gesui` (
     * `id` int(11) NOT NULL,
     * `salaryTimeId` int(11) DEFAULT NULL,
     * `salTime` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
     * `geSui_type` int(2) DEFAULT '0',
     * `opId` int(5) DEFAULT NULL,
     * `comId` int(11) DEFAULT NULL,
     * PRIMARY KEY (`id`)
     * ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
     *
     * @param unknown_type $date
     */
    function searchSalaryGeShuiTimeByDateAndComId($date, $comId) {
        $sql = "select *  from OA_gesui where salTime='$date' and comId=$comId ";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    function addSalGeShui($geshuiPO) {
        $sql = "insert into OA_gesui (salaryTimeId,salTime,geSui_type,comId) values
    	     ({$geshuiPO['salaryTimeId']},'{$geshuiPO['salTime']}',{$geshuiPO['geSui_type']},{$geshuiPO['comId']})";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function searchSalaryTimeBy_id($date) {
        $sql = "select st.*,c.company_name from OA_salarytime st,OA_company c where  st.companyId=c.id  and st.id=$date";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    // 年终奖
    function searchNianSalaryTimeBy_id($date) {
        $sql = "select st.*,c.company_name from OA_salarytime_other st,OA_company c where  st.companyId=c.id  and st.id=$date";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    function searchSalaryMovementBy_SalaryId($sid) {
        $sql = "select *  from  OA_salarymovement where salaryId=$sid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function searchErSalaryMovementBy_SalaryId($sid) {
        $sql = "select *  from  OA_er_movement where ersalaryId=$sid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function delSalaryMovement_BySalaryId($timeid) {
        $sql = "delete from  OA_salarymovement where OA_salarymovement.salaryId in (select id from OA_salary where OA_salary.salaryTimeId=$timeid)";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function delErSalaryMovement_BySalaryId($timeid) {
        $sql = "delete from  OA_er_movement where OA_er_movement.ersalaryId in (select id from OA_er_salary where OA_er_salary.salaryTimeId=$timeid)";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function delSalaryBy_TimeId($timeid) {
        $sql = "delete from  OA_salary  where salaryTimeId=$timeid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function delNianSalaryBy_TimeId($timeid) {
        $sql = "delete from  OA_nian_salary  where salaryTimeId=$timeid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function delErSalaryBy_TimeId($timeid) {
        $sql = "delete from  OA_er_salary  where salaryTimeId=$timeid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function delSalaryTimeBy_Id($timeid) {
        $sql = "delete from  OA_salarytime  where id=$timeid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function delNianSalaryTimeBy_Id($timeid) {
        $sql = "delete from  OA_salarytime_other  where id=$timeid";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function getSalaryListByComId($comid, $chequeType=null) {
        // $chequeTypeNum=$chequeType-1;
        $sql = "select *  from  OA_salarytime where companyId=$comid  ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function getSalaryListByComName($comid) {
        $sql = "select  s.id,s.salaryTime  from  OA_salarytime  s  ,OA_company  c where s.companyId = c.id AND c.company_name = '{$comid}' order by s.salaryTime DESC ";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function searchSalaryTimeApprovalCount($where) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT
	count(*) AS cnt
FROM
	OA_bill b,
	OA_salarytime st,
	(
		SELECT
			c.id,
			c.company_name
		FROM
			OA_admin_company ac,
			OA_company c
		WHERE
			c.id = ac.companyId
		AND ac.adminId = $id
	) a
WHERE
	bill_type = 4
AND b.salaryTime_id = st.id
AND a.id = st.companyId";
        if($where!=null){
            if($where['companyName']!=""){
                $sql.=" and c.company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and st.salaryTime like '%{$where['salaryTime']}%' ";
            }
            if ($where ['op_salaryTime'] != "") {
                $sql .= " and st.op_salaryTime>='{$where['op_time']}' and st.op_salaryTime<'{$where['op_salaryTime']}' ";
            }
            if ($where ['bill_value'] != "") {
                $sql .= " and b.bill_value='{$where['bill_value']}'";
            }
        }
        $result = $this->g_db_query($sql);
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }
    function searchSalaryTimeApprovalPage($start=NULL,$limit=NULL,$sort=NULL,$where) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "SELECT
	a.company_name,
	b.bill_value,
	b.id billid,
	st.salaryTime,
	st.op_salaryTime,
	st.id
FROM
	OA_bill b,
	OA_salarytime st,
	(
		SELECT
			c.id,
			c.company_name
		FROM
			OA_admin_company ac,
			OA_company c
		WHERE
			c.id = ac.companyId
		AND ac.adminId = $id
	) a
WHERE
	bill_type = 4
AND b.salaryTime_id = st.id
AND a.id = st.companyId";
        if($where!=null){
            if($where['companyName']!=""){
                $sql.=" and company_name like '%{$where['companyName']}%' ";
            }
            if ($where ['salaryTime'] != "") {
                $sql .= " and st.salaryTime like '%{$where['salaryTime']}%' ";
            }
            if ($where ['op_salaryTime'] != "") {
                $sql .= " and st.op_salaryTime>='{$where['op_time']}' and st.op_salaryTime<'{$where['op_salaryTime']}' ";
            }
            if ($where ['bill_value'] != "") {
                $sql .= " and b.bill_value='{$where['bill_value']}'";
            }
        }
        if($sort){
            $sql.=" order by st.$sort";
        }
        if($start>=0&&$limit){
            $sql.=" limit $start,$limit";
        }
        $result=$this->g_db_query($sql);
        return $result;
    }
    function searchSalTimeIdByCompanyName($com,$sal) {
        $sql = "SELECT
	s.id
FROM
	OA_salarytime s,
	OA_company c
WHERE
	s.companyId = c.id
AND company_name = '$com'
AND salaryTime = '$sal'";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function saveSalaryBill($billArray) {
        $sql = "INSERT INTO OA_bill (salaryTime_id,bill_no,bill_type,bill_date,bill_item,bill_value,bill_state,text) VALUES
    	     ({$billArray['salaryTime_id']},'{$billArray['bill_no']}','{$billArray['bill_type']}','{$billArray['bill_date']}',
    	     '{$billArray['bill_item']}','{$billArray['bill_value']}',{$billArray['bill_state']},'{$billArray['text']}')";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function updateSalaryTimeState($state, $salaryTimeId) {
        $sql = " update OA_salarytime set salary_state=$state where id=$salaryTimeId";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function searchSalaryListByComId($comId, $it = null) {
        $sql = "select OA_salarytime.*,OA_company.company_name  from OA_salarytime,OA_company where OA_company.id=OA_salarytime.companyId";
        if($comId!=null){
            $sql.=" and OA_company.id=$comId";
        }
        if ($it == 1) {
            $sql .= " and OA_salarytime.salary_state>0";
        }
        $sql .= " order by  OA_salarytime.salaryTime desc";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function searchSalaryListCountByComId($comId, $it = null) {
        $sql = "select count(*) as cnt  from OA_salarytime,OA_company where OA_company.id=OA_salarytime.companyId";
        if($comId!=null){
            $sql.=" and OA_company.id=$comId";
        }
        if ($it == 1) {
            $sql .= " and OA_salarytime.salary_state>0";
        }
        $sql .= " order by  OA_salarytime.salaryTime ";
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
        return $result;
    }
    function searchCountBill($salaryTimeId, $billType) {
        $sql = "select count(*) as count from OA_bill where salaryTime_id=$salaryTimeId and bill_type=$billType";
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
    function searchBillById($bilId) {
        $sql = "select *  from OA_bill where id=$bilId ";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }
    function updateBillById($bill) {
        $sql = "update OA_bill  set bill_item='" . $bill ['bill_item'] . "' , bill_value=" . $bill ['bill_value'] . " where id={$bill['id']} ";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function delBillById($bill) {
        $sql = "delete from  OA_bill  where id={$bill} ";
        $result = $this->g_db_query ( $sql );
        return $result;
    }

    /**
     * 根据工资月份和身份证查询某员工的应发合计
     */
    function searchSalBy_EnoAndSalTime($salaryTime, $eno) {
        $sql = "select OA_salarytime.*,OA_salary.*  from OA_salarytime,OA_salary
        where OA_salary.salaryTimeId=OA_salarytime.id  and OA_salarytime.salaryTime='$salaryTime'
              and OA_salary.employid='$eno' ";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }

    /**
     * /**
     * 根据工资月份和身份证查询某员工的应发合计
     */
    function searchSalBy_EnoAndSalTimeId($salaryTime, $eno) {
        $sql = "select *  from OA_salary
        where salaryTimeId=$salaryTime   and employid='$eno' ";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }

    /**
     * 根据模糊查询，查询公司名称列表
     */
    function getCompanyLisyByName($comName) {
        $id = $_SESSION ['admin'] ['id'];
        $sql = "select c.id ,c.company_name  from OA_company c,OA_admin_company a  where  a.companyId = c.id
		and c.company_name like '%$comName%' and a.adminId = $id";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function getCompanyLisyByNameNoAdmin($comName) {
        $sql = "select c.id ,c.company_name  from OA_company c,OA_admin_company a  where  a.companyId = c.id
		and c.company_name like '%$comName%'";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function updateLeijiyue($leiji, $timeId) {
        $sql = "update OA_salarytime set salary_leijiyue=$leiji where id=$timeId ";

        $result = $this->g_db_query ( $sql );
        return $result;
    }
    /**
     * 修改工资的身份证号
     */
    function updateSalaryEmNoByEmNo($eNo, $yuan) {
        $sql = "update OA_salary set employid='$eNo' where employid='$yuan' ";

        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function updateNianSalaryEmNoByEmNo($eNo, $yuan) {
        $sql = "update OA_nian_salary set employid='$eNo' where employid='$yuan' ";

        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function updateErSalaryEmNoByEmNo($eNo, $yuan) {
        $sql = "update OA_er_salary set employid='$eNo' where employid='$yuan' ";

        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function updateSalTimeMarkBySalTimeId($mark, $timeId) {
        $sql = "update OA_salarytime set mark='$mark' where id=$timeId ";

        $result = $this->g_db_query ( $sql );
        return $result;
    }

    function searchAccountListCount($where = null) {
        $sql = "select count(*) as cnt  from OA_account where 1=1";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and companyName like '%{$where['companyName']}%' ";
            }
            if ($where ['transactionDateb'] != "") {
                $sql .= " and transactionDate> '{$where['transactionDateb']}' ";
            }
            if ($where ['transactionDatea'] != "") {
                $sql .= " and transactionDate< '{$where['transactionDatea']}' ";
            }
            if ($where ['accountsType'] != "") {
                $sql .= " and accountsType = '{$where['accountsType']}' ";
            }
            if ($where ['accountsRemark'] != "") {
                $sql .= " and accountsRemark like '%{$where['accountsRemark']}%' ";
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }
    function searchAccountListPage($start = NULL, $limit = NULL, $sort = NULL, $where = null) {
        $sql = "select * from OA_account where 1=1";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and companyName like '%{$where['companyName']}%' ";
            }
            if ($where ['companyId'] != "") {
                $sql .= " and companyId = {$where ['companyId']}";
            }
            if ($where ['transactionDateb'] != "") {
                $sql .= " and transactionDate> '{$where['transactionDateb']}' ";
            }
            if ($where ['transactionDatea'] != "") {
                $sql .= " and transactionDate< '{$where['transactionDatea']}' ";
            }
            if ($where ['accountsType'] != "") {
                $sql .= " and accountsType = '{$where['accountsType']}' ";
            }
            if ($where ['accountsRemark'] != "") {
                $sql .= " and accountsRemark like '%{$where['accountsRemark']}%' ";
            }
        }
        if ($sort) {
            $sql .= " order by $sort";
        }
        if ($start >= 0 && $limit) {
            $sql .= " limit $start,$limit";
        }

        //echo $sql;
        $list = $this->g_db_query ( $sql );
        return $list;
    }

    function updateAccount($id, $comname){
        $sql = "
UPDATE OA_account
SET companyName = '$comname'
WHERE
	id = $id";
        $result = $this->g_db_query($sql);
        return $result;
    }
    function searchAccoutValuePresent(){
        $sql = "select transactionDate,
	accountsType,
    companyId,
	companyName,
	jiaoyi_jine,
	accountsValue,
	remark,
	accountsRemark,
	companyBank  from OA_account ORDER BY id DESC LIMIT 1;";
        $result = $this->g_db_query($sql);
        return mysql_fetch_array($result);
    }

    function insertAccounts($accountsArray){
        $sql = "
 insert into OA_account (
	transactionDate,
	accountsType,
    companyId,
	companyName,
	jiaoyi_jine,
	accountsValue,
	remark,
	accountsRemark,
	companyBank
)
values
	(
	'{$accountsArray['transactionDate']}',
	'{$accountsArray['accountsType']}',
		{$accountsArray['companyId']},
		'{$accountsArray['companyName']}',
		'{$accountsArray['jiaoyiJin']}',
		'{$accountsArray['accountsValue']}',
		'{$accountsArray['remark']}',
		'{$accountsArray['accountsRemark']}',
		'{$accountsArray['companyBank']}'
	)";
        $list = $this->g_db_query ( $sql );
        if ($list) {
            return $this->g_db_last_insert_id ();
        } else {
            return false;
        }
    }

    function getComByComLevel($comId){
        $sql = "SELECT
	id,company_name
FROM
	OA_company
WHERE
	company_level = $comId";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function getCompanyById($comId) {
        $sql = "select *  from OA_company where  id=$comId";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array($result);
    }

    function getApprovalBySalaryTimeId($id) {
        $sql = "SELECT
	*
FROM
	OA_bill
WHERE
	salaryTime_id = $id
AND bill_value <=2";
        $result = $this->g_db_query ( $sql );
        return $result;
    }

    function delAccountsById($id) {
        $sql = "DELETE
FROM
	OA_account
WHERE
	id = $id";
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function searchOaDuizhangByShouruIdAndSalTimeId($id,$salTimeId = null) {
        $sql = "select *
FROM
	OA_duizhang
WHERE
	shouru_id = $id ";
        if ($salTimeId) {
            $sql.=" and salTime_id =$salTimeId ";
        }
        $list = $this->g_db_query ( $sql );
        return $list;
    }
    function saveOaDuizhang($data){
        $sql = "insert into OA_duizhang
        (shouru_id,
        salTime_id,
        sal_type,
        jisuan_json,
        jisuan_status,
        more,
        shouru_jine,
        jisuan_yue)
        values
        (
        {$data['shouru_id']},
        {$data['salTime_id']},
        {$data['sal_type']},
        '{$data['jisuan_json']}',
        {$data['jisuan_status']},
        '{$data['more']}',
        {$data['shouru_jine']},
        {$data['yue']}
        )";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function updateOaDuiJson($data){
        $sql = "update OA_duizhang
        set
        jisuan_json = '{$data['jisuan_json']}',
        jisuan_yue = {$data['yue']},
        jisuan_status = {$data['jisuan_status']}
        where shouru_id = {$data['shouru_id']}
        and  salTime_id = {$data['salTime_id']}";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
    function updateAccountDuizhangValueById ($accountId,$duizhangYue) {
        //duizhangYue
        $sql = "update OA_account
        set
        duizhangYue = $duizhangYue
        where id = $accountId ";
        $result = $this->g_db_query ( $sql );
        return $result;
    }
}
?>
