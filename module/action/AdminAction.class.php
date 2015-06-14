<?php
require_once("module/form/".$actionPath."Form.class.php");
require_once("module/dao/".$actionPath."Dao.class.php");
require_once("module/dao/BaseDataDao.class.php");

require_once("tools/fileTools.php");

require_once("tools/excel_class.php");

require_once ("tools/Classes/PHPExcel.php");

class AdminAction extends BaseAction{
    /*
        *
        * @param $actionPath
        * @return AdminAction
        */
    function AdminAction($actionPath)
    {
        parent::BaseAction();
        $this->objForm  = new AdminForm();
        $this->objForm->setFormData("adminDomain",$this->admin);
        $this->actionPath = $actionPath;
    }
    function dispatcher(){
        // (1) mode set
        $this->setMode();
        // (2) COM initialize
        $this->initBase($this->actionPath);
        // (3) controll -> Model
        $this->controller();
        // (4) view
        $this->view();
        // (5) closeConnect
        $this->closeDB();
    }
    function setMode()
    {
        // 模式设定
        $this->mode = $_REQUEST["mode"];
    }
    function controller()
    {
        // Controller -> Model
        switch($this->mode) {
            case "input" :
                $this->getAdminList();
                break;
            case "delete" :
                $this->adminDelete();
                break;
            case "add" :
                $this->adminAdd();
                break;
            case "checklogin" :
                $this->checklogin();
                break;
            case "toOpLog" :
                $this->getOpLog();
                break;
            case "filesUpload" :
                $this->filesUpload();
                break;
            case "upload" :
                $this->filesUp();
                break;
            case "excelToHtml" :
                $this->newExcelToHtml();
                break;
            case "del":
                $this->fileDel();
                break;
            case "fileDownload":
                $this->fileDownload();
                break;
            case "fileProDownload":
                $this->fileProDownload();
                break;
            case "customerImport":
                $this->customerImport();
                break;
            case "productImport":
                $this->productImport();
                break;
            case "toUpdate":
                $this->toUpdate();
                break;
            case "toBackUp":
                $this->toBackUp();
                break;
            case "logoff":
                $this->logoff();
                break;
            case "test":
                $this->test();
                break;
            case "toNoticePage":
                $this->toNoticePage();
                break;
            default :
                $this->modelInput();
                break;
        }
    }
    function toNoticePage () {
        $this->mode	=	"toNoticePage";
    }
    function test () {
        $this->mode	=	"test";
    }
    function modelInput() {
        $this->mode	=	"toIndex";
        /*$user = $_SESSION ['admin'];
        $uid = $user['user_id'];
        $this->objDao = new SalaryDao();
        $employPO = $this->objDao->getEmByEid($uid);
        $company = $this->objDao->searchCompanyByName($employPO['e_company']);
        $this->objDao = new CompanyDao();
        $result = $this ->objDao->getNoticeByCompanyId($company['id']);
        $this->objForm->setFormData("notice",$result);*/
    }
    /**
     * 得到管理员列表
     */
    function getAdminList(){
        $this->mode="tolist";
        $today=date("Y-m-d");//当天日期
        //查询管理员详细信息
        $this->objDao=new AdminDao();
        //开始事务
        //取得管理员信息
        $exmsg=new EC();//设置错误信息类
        $result=$this->objDao->getAdminList();
        if(!$result){
            $exmsg->setError(__FUNCTION__, "get admin list  faild ");
            $this->objForm->setFormData("warn","管理员列表失败！");
            throw new Exception ($exmsg->error());
        }
        $this->objForm->setFormData("adminlist",$result);
    }
    /**
     * 删除管理员
     */
    function  adminDelete(){
        $today=date("Y-m-d");//当天日期
        //查询管理员详细信息
        global $loginusername;
        $this->objDao=new AdminDao();
        //开始事务
        $this->objDao->beginTransaction();
        //取得管理员信息
        $admin=$_SESSION['admin'];
        $exmsg=new EC();//设置错误信息类
        $adminId=$_REQUEST["aid"];
        $result=$this->objDao->updateAdminToDelete($adminId);
        if(!$result){
            $exmsg->setError(__FUNCTION__, "delete admin   faild ");
            //事务回滚
            $this->objDao->rollback();
            $this->objForm->setFormData("warn","删除管理员操作失败！");
            throw new Exception ($exmsg->error());
        }
        $opLog=array();
        $opLog['who']=$admin['id'];
        $opLog['what']=$adminId;
        $opLog['Subject']=OP_LOG_DELETE_ADMIN;
        $opLog['memo']='';
        //{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
        $rasult=$this->objDao->addOplog($opLog);
        if(!$rasult){
            $exmsg->setError(__FUNCTION__, "addAdmin  add oplog  faild ");
            $this->objForm->setFormData("warn","删除管理员操作失败");
            //事务回滚
            $this->objDao->rollback();
            throw new Exception ($exmsg->error());
        }
        //事务提交
        $this->objDao->commit();
        $this->getAdminList();
    }
    /**
     * 添加管理员
     */
    function adminAdd(){
        $admin=array();
        $admin['name']=$_REQUEST["byid"];
        $admin['password']=$_REQUEST["pass"];
        $admin['admin_type']=$_REQUEST["user_type"];
        $admin['memo']=$_REQUEST["memo"];

        //'{$admin['name']}',{$admin['admin_type']},'{$admin['mail_addr']}',now(),'{$admin['memo']}'
        $today=date("Y-m-d");//当天日期
        //查询管理员详细信息
        global $loginusername;
        $this->objDao=new AdminDao();
        $adminResult=$this->objDao->getAdmin($admin['name']);
        if(!empty($adminResult)){
            $this->objForm->setFormData("warn","该用户已存在！");
            $this->getAdminList();
            return;
        }
        //开始事务
        $this->objDao->beginTransaction();
        //取得管理员信息
        //=$this->objDao->getAdmin($loginusername);
        $adminPO=$_SESSION['admin'];
        $exmsg=new EC();//设置错误信息类
        $result=$this->objDao->addAdmin($admin);
        if(!$result){
            $exmsg->setError(__FUNCTION__, "delete admin   faild ");
            //事务回滚
            $this->objDao->rollback();
            $this->objForm->setFormData("warn","添加管理员操作失败！");
            throw new Exception ($exmsg->error());
        }
        $saveLastId=$this->objDao->g_db_last_insert_id();
        $opLog=array();
        $opLog['who']=$adminPO['id'];
        $opLog['what']=$saveLastId;
        $opLog['Subject']=OP_LOG_ADD_ADMIN;
        $opLog['memo']='';
        //{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
        $rasult=$this->objDao->addOplog($opLog);
        if(!$rasult){
            $exmsg->setError(__FUNCTION__, "addAdmin  add oplog  faild ");
            $this->objForm->setFormData("warn","添加管理员操作失败");
            //事务回滚
            $this->objDao->rollback();
            throw new Exception ($exmsg->error());
        }
        //事务提交
        $this->objDao->commit();
        $this->getAdminList();
    }
    function checklogin(){
        $name = $this->getParam('usrname');
        $pass = $this->getParam('password');
        $login_mode = $this->getParam('login_mode');
        $this->objDao=new AdminDao();
        $check=$this->objDao->checklogin($name,$pass);
        $result=$this->objDao->updateAdminLoginTime($check);
        if(empty($check)){
            if ($login_mode == 'employ'){
                $this->mode="employ_login";

            } else {
                $this->mode="login";
            }

            $this->objForm->setFormData("login_error","输入用户名密码错误请重新登录");
        }else{

            if ($check['user_type'] == 2) {
                $employ = $this->objDao->getEmByEid($check['user_id']);
                $check['real_name'] = $employ['e_name'];
            } elseif ($check['user_type'] == 1) {
                $company = $this->objDao->getCompanyById($check['user_id']);
                $check['real_name'] = $company['company_name'];
            } elseif ($check['user_type'] == 3) {
                $this->objDao = new BaseDataDao();
                $department = $this->objDao->getDepartmentsById($check['user_id']);
                $check['real_name'] = $department['name'];
                $companyTree = $this->objDao->getDepartmentsById($department['pid']);
                $companyId = $companyTree['company_id'];
                $check['company_id'] =$companyId;
                $check['company_name'] =$companyTree['name'];
            }
            $_SESSION['admin']=$check;
            header("Location: index.php");
        }
    }
    /**
     * 得到操作日志列表
     */
    function getOpLog(){
        $this->mode="toOpLog";
        $this->objDao=new AdminDao();
        $admin=$_SESSION['admin'];
        $whereCount="1=1";//查询总数条件
        $listwhere="";
        if($admin['admin_type']!=ADMIN_TYPE_SYS){//如果是非超级管理员，只能看见自己的操作日志
            $whereCount.=" and who={$admin['id']}";
            $listwhere=" and who={$admin['id']}";
        }
        $sum =$this->objDao->g_db_count("or_log","*",$whereCount);
        //$sum=10;
        $pagesize=PAGE_SIZE;
        //$sum=$rs['sum'];
        $count = intval($_REQUEST["c"]);
        $page = intval($_REQUEST["p"]);
        if ($count == 0){
            $count = $pagesize;
        }
        if ($page == 0){
            $page = 1;
        }

        $startIndex = ($page-1)*$count;
        $total = $sum;
        $pageindex=$page;
        //得到商品列表
        $listwhere.=" order by or_log.time desc limit $startIndex,$pagesize";
        $opLogList=array();
        $result=$this->objDao->getOpLogList($listwhere);
        $i=0;
        global $LOGNAME;
        while($row=mysql_fetch_array($result)){
            if($row['subject']==OP_LOG_DELETE_ADMIN||$row['subject']==OP_LOG_ADD_ADMIN){
                //如果是对管理员操作查询操作的管理员名称
                $opAdmin=$this->objDao->getAdminById($row['what']);
                $row['whatname']=$opAdmin['name'];
            }else{//否则查询相应操作的产品名称
                //$opProduct=$this->objDao->getTaskById($row['what']);
                //$row['whatname']=$opProduct['task_name'];
            }
            $opLogList[$i]=$row;
            $i++;
        }
        $this->objForm->setFormData("startIndex",$startIndex);
        $this->objForm->setFormData("total",$total);
        $this->objForm->setFormData("pageindex",$pageindex);
        $this->objForm->setFormData("pagesize",$pagesize);
        $this->objForm->setFormData("opLogList",$opLogList);
    }
    function filesUpload(){
        $this->mode="toUpload";
        $op=new fileoperate();
        $files=$op->list_filename("upload/",1);
        $this->objForm->setFormData("files",$files);
    }
    function filesUp(){
        $exmsg=new EC();
        $fullfilepath = UPLOADPATH.$_FILES['file']['name'];
        $errorMsg="";
        //var_dump($_FILES);
        $fileArray=split("\.",$_FILES['file']['name']);
        //var_dump($fileArray);
        if(count($fileArray)!=2){
            $this->mode="toUpload";
            $errorMsg='文件名格式 不正确';
            $this->objForm->setFormData("error",$errorMsg);
            return;
        }else if($fileArray[1]!='xls'){
            $this->mode="toUpload";
            $errorMsg='文件类型不正确，必须是xls类型';
            $this->objForm->setFormData("error",$errorMsg);
            return;
        }
        if($_FILES['file']['error'] != 0){
            $error = $_FILES['file']['error'];
            switch($error)
            {
                case 1:
                    $errorMsg= '1,上传的文件超过了php.ini中  upload_max_filesize选项限制的值.';
                    break;
                case 2:
                    $errorMsg= '2,上传文件的大小超过了HTML表单中MAX_FILE_SIZE  选项指定的大小';
                    break;
                case 3:
                    $errorMsg= '3,文件只有部分被上传';
                    break;
                case 4:
                    $errorMsg= '4,文件没有被上传';
                    break;
                case 6:
                    $errorMsg= '找不到临文件夹';
                    break;
                case 7:
                    $errorMsg= '文件写入失败';
                    break;
            }
        }
        if($errorMsg!=""){
            $this->mode="toUpload";
            $this->objForm->setFormData("error",$errorMsg);
            return;
        }
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $fullfilepath)){//上传文件
            //print_r($_FILES);print_r($fullfilepath);
            //$this->objDao->rollback();
            $this->objForm->setFormData("error","文件导入失败");
            throw new Exception(UPLOADPATH." is a disable dir");

            //die("UPLOAD FILE FAILED:".$_FILES['plusfile']['error']);
        }else{
            $this->mode="toUpload";
            $succMsg='文件导入成功';
            $this->objForm->setFormData("succ",$succMsg);

        }
        /*$adminPO=$_SESSION['admin'];
        $opLog=array();
        $opLog['who']=$adminPO['id'];
        $opLog['what']=0;
        $opLog['Subject']=OP_LOG_UPLOAD_FILE;
        $opLog['memo']='文件名称：'.$_FILES['file']['name'];
        //{$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
        $this->objDao=new AdminDao();
        $rasult=$this->objDao->addOplog($opLog);
        if(!$rasult){
            $exmsg->setError(__FUNCTION__, "uploadfile  add oplog  faild ");
            $this->objForm->setFormData("warn","失败");
            //事务回滚
            //$this->objDao->rollback();
            throw new Exception ($exmsg->error());
        }*/
        $op=new fileoperate();
        $files=$op->list_filename("upload/",1);
        $this->objForm->setFormData("files",$files);
    }
    function excelToHtml(){
        $fname=$_GET['fname'];
        $err=Read_Excel_File("upload/".$fname,$return);
        if($err!=0){
            $this->objForm->setFormData("error",$err);
        }
        $this->objForm->setFormData("salarylist",$return);
        $this->mode="excelList";
        /*for ($i=0;$i<count($return[Sheet1]);$i++)
         {
            for ($j=0;$j<count($return[Sheet1][$i]);$j++)
            {
            echo $return[Sheet1][$i][$j]."|";
            }
            echo "<br>";
            }
            exit;*/
    }
    function newExcelToHtml() {
        $fname = $_REQUEST ['fname'];
        $checkType = $_REQUEST ['checkType'];
        $path = "upload/" . $fname;
        $_ReadExcel = new PHPExcel_Reader_Excel2007 ();
        if (!$_ReadExcel->canRead($path))
            $_ReadExcel = new PHPExcel_Reader_Excel5 ();
        $_phpExcel = $_ReadExcel->load($path);
        $_newExcel = array();
        for ($_s = 0; $_s < 1; $_s++) {
            $_currentSheet = $_phpExcel->getSheet($_s);
            $_allColumn = $_currentSheet->getHighestColumn();
            $_allRow = $_currentSheet->getHighestRow();
            $temp = 0;
            for ($_r = 1; $_r <= $_allRow; $_r++) {
                for ($_currentColumn = 'A'; $_currentColumn <= $_allColumn; $_currentColumn++) {
                    $address = $_currentColumn . $_r;
                    $val = $_currentSheet->getCell($address)->getValue();
                    $_newExcel ['moban'] [$temp] [] = $val;
                }
                $temp++;
            }
        }
        $this->objForm->setFormData("salarylist", $_newExcel);
        $this->mode = "excelList";
    }
    function fileDel(){
        $this->mode="toUpload";
        $fname=$_GET['fname'];
        $op=new fileoperate();
        $mess=$op->del_file("upload/",$fname);
        $files=$op->list_filename("upload/",1);
        $this->objForm->setFormData("files",$files);
        $this->objForm->setFormData("error",$mess);
    }
    function fileDownload(){
        $file =DOWNLOAD_KEHU_PATH;
        //$this->mode="toUpload";
        echo $file;
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);

        }/*else{
   	echo "!AAAA";
   } */
        $this->filesUpload();
    }
    function fileProDownload(){
        $file =DOWNLOAD_CHANPIN_PATH;
        //$this->mode="toUpload";
        echo $file;
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);

        }/*else{
   	echo "!AAAA";
   } */
        $this->filesUpload();
    }
    function customerImport(){
        session_start();
        $salaryList=$_SESSION['excellsit'];
        //var_dump($_SESSION);
        $this->mode="afterImportPage";

        //$error[$i]["error"]="{$salaryList[Sheet1][$i][$shenfenzheng]}:未查询到该员工身份类别！";
        $salaryList[Sheet1][0];
        //var_dump($salaryList['moban']);
        $error=array();
        $message=array();
        $message['count']=count($salaryList['moban']);
        //循环添加客户信息
        for ($i=1;$i<count($salaryList['moban']);$i++){

            $customerPO=array();
            $customer['custo_name']=trim($salaryList['moban'][$i][1]);
            $this->objDao=new CustomerDao();
            $customerpo=$this->objDao->searchCustomerByName($customer['custo_name']);
            if($customerpo){
                $error[$i]["error"]="{$salaryList['moban'][$i][1]}:该公司字段已经添加过了";
                $message['count']-=1;
                continue;
            }
            $customer['op_id']=$salaryList['moban'][$i][11];
            $customer['custo_type']=$salaryList['moban'][$i][7];
            $customer['custo_discount']=$salaryList['moban'][$i][5];
            $customer['adress']=trim($salaryList['moban'][$i][2]);
            $customer['post_no']=trim($salaryList['moban'][$i][3]);
            $customer['telphone_no']=trim($salaryList['moban'][$i][4]);
            $customer['moveTel_no']="";
            $customer['faxTel_no']="";
            $customer['custo_mail']="";
            $customer['accounter_name']="";
            $customer['bank_name']="";
            $customer['total_money']=$salaryList['moban'][$i][8];
            $custLevel="潜在客户";
            if($customer['total_money']<500000&&$customer['total_money']>0){
                $custLevel="初级客户";
            }elseif($customer['total_money']>=500000&&$customer['total_money']<2000000){
                $custLevel="中级客户";
            }elseif($customer['total_money']>=2000000){
                $custLevel="高级客户";
            }
            $customer['custo_level']=$custLevel;
            $customer['custoHaed_name']=trim($salaryList['moban'][$i][9]);
            $customer['custoHead_level']="";
            $customer['remarks']=trim($salaryList['moban'][$i][10]);
            $customer['custo_info']=trim($salaryList['moban'][$i][12]);
            $customer['bank_name']=trim($salaryList['moban'][$i][13]);
            $customer['bank_no']=trim($salaryList['moban'][$i][14]);

            $result=$this->objDao->addCustomer($customer);
            if(!$result){
                $error[$i]["error"]="{$salaryList['moban'][$i][1]}:该公司字段内容不正确无法加入";
                $message['count']-=1;
            }
            if($i%10==0){
                echo "sleepstart";
                sleep(0.2);
                echo "sleepstop";
            }
        }
        /*		var_dump($error);
                exit;
        */   $message['name']="成功导入{$message["count"]}条客户信息";
        $this->objForm->setFormData("error",$error);
        $this->objForm->setFormData("message",$message);
    }
    function productImport(){
        session_start();
        $salaryList=$_SESSION['excellsit'];
        $this->mode="afterImportPage";

        $error=array();
        $message=array();
        $message['count']=count($salaryList['moban']);
        //var_dump($salaryList['moban']);
        //循环添加客户信息
        for ($i=1;$i<count($salaryList['moban']);$i++){
            $ProductPO=array();
            $ProductPO['pro_code']=trim($salaryList['moban'][$i][0]);
            $ProductPO['pro_spec']=trim($salaryList['moban'][$i][1]);
            $ProductPO['pro_unit']=trim($salaryList['moban'][$i][2]);
            $ProductPO['pro_group']=trim($salaryList['moban'][$i][5]);
            $exmsg=new EC();//设置错误信息类
            $this->objDao=new ProductDao();
            $productPo=$this->objDao->getProductByCode($ProductPO['pro_code']);
            if($productPo){
                //$mess="({$product['pro_code']})此产品型号已经添加过了";
                $error[$i]["error"]="({$productPo['pro_code']})此产品型号已经添加过了";
                //$this->objForm->setFormData("error",$mess);
                continue;
            }
            $result=$this->objDao->addProduct($ProductPO);
            if(!$result){
                $error[$i]["error"]="{$salaryList['moban'][$i][1]}:该产品字段内容不正确无法加入";
                $message['count']-=1;
            }
            if($i%10==0){
                //echo "sleepstart";
                sleep(0.2);
                //echo "sleepstop";
            }
        }
        /*		var_dump($error);
                exit;
        */   $message['name']="成功导入{$message["count"]}条产品信息";
        $this->objForm->setFormData("error",$error);
        $this->objForm->setFormData("message",$message);
    }
    function toUpdate(){
        $this->mode="toUpdate";
        $fname=$_GET['fname'];
        $err=Read_Excel_File("upload/".$fname,$return);
        if($err!=0){
            $this->objForm->setFormData("error",$err);
        }
        $this->objForm->setFormData("salarylist",$return);
    }
    function toBackUp(){
        $this->mode="toBackUp";
    }
    function backupByAdmin(){
        $time=$_REQUEST['times'];
        $now=date('Y-m-d H:i:s',time());
        //生成一个连接
        $db_connect_backup=mysql_connect("127.0.0.1","root","") or die("Unable to connect to the MySQL!");
        $db_connect=mysql_connect("localhost","root","") or die("Unable to connect to the MySQL!");
        mysql_query('set names utf8',$db_connect_backup);
        mysql_select_db("order_backup",$db_connect_backup);
        //选择一个需要操作的数据库
        $sql="select * from or_order order by op_time desc limit 1 ";//and isjiekuan=1
        $result=mysql_query($sql);
        //查询备份订单表最大的当前日期
        $orderBpo=mysql_fetch_array($result);
        $orderMaxTime=$orderPO['op_time'];
        mysql_select_db("order",$db_connect);
        $sql_query_order="select * from or_order where op_time >= '".$orderMaxTime."' ";
        $sql_query_order_single="select id from or_order where id={$order_po['id']}";
        $sql_update_order_single="select id from or_order where id={$order_po['id']}";
        $order_list=mysql_query($sql_query_order,$db_connect);
        //$orderPo=
        while ($order_po=mysql_fetch_array($order_list)) {
            mysql_select_db("order_backup",$db_connect);


            $order_backup_po=mysql_query($sql_query_order_single,$db_connect);
            if(mysql_fetch_array($order_backup_po)){

            }
        }
        echo $str;
        exit;

    }
    //function
}


$objModel = new AdminAction($actionPath);
$objModel->dispatcher();



?>
