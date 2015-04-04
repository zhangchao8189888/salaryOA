<?php
/**
 * 社保dao
 */
class SocialSecurityDao extends BaseDao {

    /**
     * @return BaseConfigDao
     */
    function SocialSecurityDao() {
        parent::BaseDao ();
    }

    function loginType(){
        $id = $_SESSION ['admin'] ['id'];
        $sql="SELECT admin_type  FROM  OA_admin  WHERE  id=$id";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }

    function searchBusinessById($id) {
        $sql = "SELECT * FROM OA_business where id =$id";
        $result = $this->g_db_query ( $sql );
        return mysql_fetch_array ( $result );
    }

    /**
     * 搜索变更业务dao
     */
    function searchBusinessCount($where) {
        $sql = "SELECT COUNT(id) AS cnt FROM OA_business where 1=1";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and companyName like '%{$where['companyName']}%' ";
            }
            if ($where ['submitTime'] != "") {
                $sql .= " and submitTime <= '{$where['submitTime']}' ";
            }
            if ($where ['employName'] != "") {
                $sql .= " and employName LIKE '%{$where['employName']}%' ";
            }
            if ($where ['socialSecurityStateId'] != "") {
                $sql .= " and socialSecurityStateId = '{$where['socialSecurityStateId']}' ";
            }
            if ($where ['businessName'] != "") {
                $sql .= " and businessName = '{$where['businessName']}' ";
            }
            if ($where ['otherName'] != "") {
                $sql .= " AND businessName NOT IN (1,2,3,4,5,6,7,8,9,10) ";
            }
        }
        $result = $this->g_db_query ( $sql );
        if (! $result) {
            return 0;
        }
        $row = mysql_fetch_assoc ( $result );
        return $row ['cnt'];
    }

    function searchBusinessPage($start = NULL, $limit = NULL, $sort = NULL, $where = null){
        $sql = "SELECT * FROM OA_business WHERE 1=1";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " AND companyName LIKE '%{$where['companyName']}%' ";
            }
            if ($where ['submitTime'] != "") {
                $sql .= " and submitTime <= '{$where['submitTime']}' ";
            }
            if ($where ['employName'] != "") {
                $sql .= " and employName LIKE '%{$where['employName']}%' ";
            }
            if ($where ['socialSecurityStateId'] != "") {
                $sql .= " and socialSecurityStateId = '{$where['socialSecurityStateId']}' ";
            }
            if ($where ['businessName'] != "") {
                $sql .= " and businessName = '{$where['businessName']}' ";
            }
            if ($where ['otherName'] != "") {
                $sql .= " AND businessName NOT IN (1,2,3,4,5,6,7,8,9,10) ";
            }
        }
        if ($sort) {
            $sql .= " order by $sort";
        }
        if ($start >= 0 && $limit) {
            $sql .= " limit $start,$limit";
        }
        $result = $this->g_db_query($sql);
        return $result;
    }

    /**
     * 增加变更业务dao
     */
    function addBusinessLog($businessLog) {
        $sql = "
        insert into OA_business (submitTime,companyId,companyName,employId,employName,businessName,serviceId,serviceName,remarks,socialSecurityStateId,socialSecurityState,employStateId,employState,tel)
        values
    	     (now(),
    	     '{$businessLog['companyId']}',
    	     '{$businessLog['companyName']}',
    	     '{$businessLog['employNumber']}',
    	     '{$businessLog['employName']}',
    	     '{$businessLog['businessName']}',
    	     '{$businessLog['serviceId']}',
    	     '{$businessLog['serviceName']}',
    	     '{$businessLog['remarks']}',
    	     '{$businessLog['socialSecurityStateId']}',
    	     '{$businessLog['socialSecurityState']}',
    	     '{$businessLog['employStateId']}',
    	     '{$businessLog['employState']}',
    	     '{$businessLog['tel']}') ";
        $result = $this->g_db_query($sql);
        return $result;
    }

    /**
     * 变更业务状态
     */
    function updateBusinessLog($adminType,$upId,$updateTypeid,$updateType,$other) {
        $name = $_SESSION ['admin'] ['name'];
        $id = $_SESSION ['admin'] ['id'];
        $sql = " UPDATE OA_business SET socialSecurityStateId =$updateTypeid,
                socialSecurityState ='$updateType'";
        if($adminType==3){
            $sql .=",serviceId = $id,serviceName =  '$name'";
        }else if($adminType==5){
            $sql .=",adminId = $id,adminName =  '$name',updateTime=now()";
            if($other['reimbursementTime']){
                $sql .=",reimbursementTime =  '{$other['reimbursementTime']}',
                reimbursementValue =  '{$other['reimbursementValue']}'";
            }
            if($other['accountTime']){
                $sql .=",accountTime =  '{$other['accountTime']}',
                accountValue =  '{$other['accountValue']}'";
            }
            if($other['grantTime']){
                $sql .=",grantTime =  '{$other['grantTime']}',
                grantValue =  '{$other['grantValue']}'";
            }
            if($other['retireTime']){
                $sql .=",retireTime =  '{$other['retireTime']}'";
            }
            if($other['accountComTime']){
                $sql .=",accountComTime =  '{$other['accountComTime']}',
                accountComValue =  '{$other['accountComValue']}'";
            }
            if($other['accountPersonTime']){
                $sql .=",accountPersonTime =  '{$other['accountPersonTime']}',
                accountPersonValue =  '{$other['accountPersonValue']}'";
            } if($other['remarks']){
                $sql .=",remarks =  '{$other['remarks']}'";
            }
        }
        $sql .=" WHERE id=$upId";
        $result = $this->g_db_query($sql);
        return $result;
    }

    function searchInsuranceCount($where){
        $sql = "SELECT COUNT(id) AS cnt FROM OA_insurance where 1=1";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and companyName like '%{$where['companyName']}%' ";
            }
            if ($where ['submitTime'] != "") {
//                $sql .= " and submitTime <= '{$where['submitTime']}' ";
            }
            if ($where ['employName'] != "") {
                $sql .= " and employName LIKE '%{$where['employName']}%' ";
            }
            if ($where ['disType'] != "") {
                if ($where ['disType'] == "1") {
                    $sql .= " and unInsuranceReason IS NOT NULL  AND unInsuranceReason <> '' ";
                }
                if ($where ['disType'] == "0") {
                    $sql .= " and unInsuranceReason ='' and paymentEndTime>now() ";
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

    function searchInsurancePage($start = NULL, $limit = NULL, $sort = NULL, $where = null){
        $sql = "SELECT * FROM OA_insurance WHERE 1=1";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " AND companyName LIKE '%{$where['companyName']}%' ";
            }
            if ($where ['submitTime'] != "") {
//                $sql .= " and submitTime <= '{$where['submitTime']}' ";
            }
            if ($where ['employName'] != "") {
                $sql .= " and employName LIKE '%{$where['employName']}%' ";
            }
            if ($where ['disType'] != "") {
                if ($where ['disType'] == "1") {
                    $sql .= " and unInsuranceReason IS NOT NULL AND unInsuranceReason <> ''";
                }
                if ($where ['disType'] == "0") {
                    $sql .= " and unInsuranceReason ='' and paymentEndTime>now()";
                }
            }
        }
        if ($sort) {
            $sql .= " order by $sort";
        }
        if ($start >= 0 && $limit) {
            $sql .= " limit $start,$limit";
        }
        $result = $this->g_db_query($sql);

        return $result;
    }

    function addInsurance($insuranceInfo){
        $sql = "
        insert into OA_insurance (submitTime,companyName,employId,employName,idClass,serviceId,serviceName,base,paymentStartTime,paymentEndTime,paymentTime,paymentValue,paymentType,remark,unInsuranceReason,explainInfo,entryTime,tel)
        values
    	     (now(),
    	     '{$insuranceInfo['companyName']}',
    	     '{$insuranceInfo['employId']}',
    	     '{$insuranceInfo['employName']}',
    	     '{$insuranceInfo['idClass']}',
    	     '{$insuranceInfo['serviceId']}',
    	     '{$insuranceInfo['serviceName']}',
    	     '{$insuranceInfo['base']}',
    	     '{$insuranceInfo['paymentStartTime']}',
    	     '{$insuranceInfo['paymentEndTime']}',
    	     '{$insuranceInfo['paymentTime']}',
    	     '{$insuranceInfo['paymentValue']}',
    	     '{$insuranceInfo['paymentType']}',
    	     '{$insuranceInfo['remark']}',
    	     '{$insuranceInfo['unInsuranceReason']}',
    	     '{$insuranceInfo['explainInfo']}',
    	     '{$insuranceInfo['entryTime']}',
    	     '{$insuranceInfo['tel']}') ";
        $result = $this->g_db_query($sql);

        return $result;
    }

    function  updateInsurance($upId,$paymentValue){
        $name = $_SESSION ['admin'] ['name'];
        $id = $_SESSION ['admin'] ['id'];
        $sql = " UPDATE OA_insurance SET paymentValue =$paymentValue,
                paymentTime =now() ,adminId = $id,adminName =  '$name',updateTime=now()  WHERE id=$upId";
        $result = $this->g_db_query($sql);
        return $result;
    }

    function searhZengjianTongjiCount($where = null) {
        $sql = "SELECT count(id) as cnt FROM OA_security WHERE 1=1";
        if ($where != null) {
            if ($where ['companyName'] != "") {
                $sql .= " and Dept like '%{$where['companyName']}%' ";
            }
            if ($where ['zengjian'] != "") {
                $sql .= "  and zengjianbiaozhi like '%{$where['zengjian']}%' ";
            }
            if($where ['shenbaozhuangtai'] != ""){
                $sql .= "  and shenbaozhuangtai = '{$where['shenbaozhuangtai']}' ";
            }
            if($where ['business_type'] != ""){
                $sql .= "  and business_type = '{$where['business_type']}' ";
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
     * 增减员状态
     */
    function updateZengjian($upid,$uptype,$remark) {
        $name = $_SESSION ['admin'] ['name'];
        $sql = " UPDATE OA_security  SET shenbaozhuangtai ='$uptype', updateTime=now(),
                caozuoren ='$name'";
        if($remark!=""){
            $sql .=",beizhu = '$remark'";
        }
        $sql .=" WHERE id=$upid";
        $result = $this->g_db_query($sql);
        return $result;
    }
}
?>
