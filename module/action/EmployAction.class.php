<?php
require_once("module/form/EmployForm.class.php");
require_once("module/dao/EmployDao.class.php");
require_once("module/dao/SalaryDao.class.php");
require_once("module/dao/BaseDataDao.class.php");
require_once("tools/excel_class.php");
require_once("tools/Classes/PHPExcel.php");
require_once("tools/Util.php");
require_once("tools/JPagination.php");

class EmployAction extends BaseAction {
    /*
        *
        * @param $actionPath
        * @return TestAction
        */
    function EmployAction($actionPath) {
        parent::BaseAction();
        $this->objForm = new EmployForm();
        $this->actionPath = $actionPath;
    }

    function dispatcher() {
        // (1) mode set
        $this->setMode();
        // (2) COM initialize
        $this->initBase($this->actionPath);
        // (3)验证SESSION是否过期
        //$this->checkSession();
        // (4) controll -> Model
        $this->controller();
        // (5) view
        $this->view();
        // (6) closeConnect
        $this->closeDB();
    }

    function setMode() {
        // 模式设定
        $this->mode = $_REQUEST['mode'];
    }

    function controller() {
        // Controller -> Model
        switch ($this->mode) {
            case "toEmployList" :
                $this->toEmployList();
                break;
            case "saveOrUpdateEmploy" :
                $this->saveOrUpdateEmploy();
                break;
            case "getEmployTemlate":
                $this->getEmployTemlate();
                break;
            case "getEmployInfo":
                $this->getEmployInfo();
                break;
            case "newemImport" :
                $this->newemImport();
                break;
            case "toEmImport" :
                $this->toEmImport();
                break;
            case "employDelByIds" :
                $this->employDelByIds();
                break;
            case "employDelById" :
                $this->employDelById();
                break;
            case "modifyEmploySort" :
                $this->modifyEmploySort();
                break;
            default :
                $this->modelInput();
                break;
        }


    }

    function modelInput() {
        $this->mode = "toAdd";
    }
    function toEmImport() {
        $this->mode = "toEmImport";
    }
    function getEmployInfo () {
        $employId = $_REQUEST['employId'];
        $this->objDao = new EmployDao();
        $employInfo = $this->objDao->getEmployById($employId);
        $this->objDao = new BaseDataDao();
        $department = $this->objDao->getDepartmentsById($employInfo['department_id']);

        $employInfo['department'] = $department['name'];
        echo json_encode($employInfo);
        exit;
    }
    function employDelById () {

        $employId = $_REQUEST['employId'];
        $this->objDao = new EmployDao();
        $result = $this->objDao->delEmployById($employId);
        $data = array();
        if ($result) {
            $data['code'] = 100000;
            $data['mess'] = '删除成功';
        } else {
            $data['code'] = 100001;
            $data['mess'] = '删除失败';
        }
        echo json_encode($data);
        exit;
    }
    function modifyEmploySort () {
        $rowData = $_REQUEST['rowData'];
        $z = 0 ;
        foreach ($rowData as $key => $val) {
            $this->objDao = new EmployDao();
            $result = $this->objDao->changeEmpIndexByEnum($key+1,$val[1]);
            if (!$result) {
                $z++;
            }
        }

        $data = array();
        if ($z == 0) {
            $data['code'] = 100000;
            $data['mess'] = '替换成功';
        } else {
            $data['code'] = 100001;
            $data['mess'] = '替换失败';
        }
        echo json_encode($data);
        exit;
    }
    function employDelByIds() {
        $employIds = $_REQUEST['employIds'];
        $employIdArr = explode(',',$employIds);

        $this->objDao = new EmployDao();
        foreach($employIdArr as $id) {
            if (empty($id)) {
                continue;
            }
            $result = $this->objDao->delEmployById($id);
        }
        $this->toEmployList();
    }
    function getEmployTemlate() {
        $file = 'template/empTemlate.xls';
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }
    function newemImport() {
        $this->mode = "toEmImport";
        set_time_limit(1800);
        $errorMsg = "";
        $fileArray = split("\.", $_FILES['file']['name']);
        if (count($fileArray) != 2) {
            $this->mode = "toUpload";
            $errorMsg = '文件名格式 不正确';
            $this->objForm->setFormData("error", $errorMsg);
            return;
        } else if ($fileArray[1] != 'xls') {
            $this->mode = "toUpload";
            $errorMsg = '文件类型不正确，必须是xls类型';
            $this->objForm->setFormData("error", $errorMsg);
            return;
        }
        if ($_FILES['file']['error'] != 0) {
            $error = $_FILES['file']['error'];
            switch ($error) {
                case 1:
                    $errorMsg = '1,上传的文件超过了php.ini中  upload_max_filesize选项限制的值.';
                    break;
                case 2:
                    $errorMsg = '2,上传文件的大小超过了HTML表单中MAX_FILE_SIZE  选项指定的大小';
                    break;
                case 3:
                    $errorMsg = '3,文件只有部分被上传';
                    break;
                case 4:
                    $errorMsg = '4,文件没有被上传';
                    break;
                case 6:
                    $errorMsg = '找不到临文件夹';
                    break;
                case 7:
                    $errorMsg = '文件写入失败';
                    break;
            }
        }
        if ($errorMsg != "") {
            $this->mode = "toEmImport";
            $this->objForm->setFormData("error", $errorMsg);
            return;
        }
        $path = $_FILES['file']['tmp_name'];
        $_ReadExcel = new PHPExcel_Reader_Excel2007();
        if (!$_ReadExcel->canRead($path)) $_ReadExcel = new PHPExcel_Reader_Excel5();
        //读取Excel文件
        $_phpExcel = $_ReadExcel->load($path);
        //获取工作表的数目
        $_sheetCount = $_phpExcel->getSheetCount();

        $return = array();
        $_excelData = array();

        //循环工作表
        //for($_s = 0;$_s<$_sheetCount;$_s++) {
        for ($_s = 0; $_s < 2; $_s++) {
            //选择工作表
            $_currentSheet = $_phpExcel->getSheet($_s);
            //取得一共有多少列
            $_allColumn = $_currentSheet->getHighestColumn();
            //取得一共有多少行
            $_allRow = $_currentSheet->getHighestRow();
            for ($_r = 1; $_r <= $_allRow; $_r++) {

                for ($_currentColumn = 'A'; $_currentColumn <= $_allColumn; $_currentColumn++) {
                    $address = $_currentColumn . $_r;
                    $val = $_currentSheet->getCell($address)->getValue();

                        $return['Sheet1'][$_r][] = $val;
                }
            }
        }
        $this->objDao = new EmployDao();
        $this->objForm->setFormData("salarylist", $return);

        $employList = array();
        $user = $_SESSION ['admin'];
        $department_id = 0;
        if ($user['user_type'] == 1) {
            $companyId = $user['user_id'];
        } elseif($user['user_type'] == 3) {
            $companyId = $user['company_id'];
            $department_id = $user['user_id'];
        }
        $company = $this->objDao->getCompanyById($companyId);
        global $userTypeName;
        $z = 1;
        for ($i = 2; $i < count($return["Sheet1"]); $i++) {
            if($return["Sheet1"][$i][1] == NULL){
                continue;
            }
            $sit = $i-1;
            $employList[$sit]['e_company_id'] = $companyId;
            $employList[$sit]['e_company'] = $company['company_name'];
            $employList[$sit]['e_name'] = $return["Sheet1"][$i][1];
            $employList[$sit]['e_num'] = $return["Sheet1"][$i][2];
            $employList[$sit]['depart'] = $return["Sheet1"][$i][0];
            $employList[$sit]['e_status'] = 1;
            $employList[$sit]['bank_name'] = $return["Sheet1"][$i][3];
            $employList[$sit]['bank_num'] = $return["Sheet1"][$i][4];
            $employList[$sit]['e_type'] = $userTypeName[$return["Sheet1"][$i][5]];
            $employList[$sit]['shebaojishu'] = findNullReturnNumber($return["Sheet1"][$i][6]);
            $employList[$sit]['gongjijinjishu'] = findNullReturnNumber($return["Sheet1"][$i][7]);
            $employList[$sit]['laowufei'] = findNullReturnNumber($return["Sheet1"][$i][8]);
            $employList[$sit]['canbaojin'] = findNullReturnNumber($return["Sheet1"][$i][9]);
            $employList[$sit]['danganfei'] = findNullReturnNumber($return["Sheet1"][$i][10]);
            $employList[$sit]['memo'] = $return["Sheet1"][$i][11];
            $employList[$sit]['e_hetong_date'] = $return["Sheet1"][$i][12];
            $employList[$sit]['e_hetongnian'] = findNullReturnNumber($return["Sheet1"][$i][13]);
            $employList[$sit]['department_id'] = $department_id;
            $employList[$sit]['e_sort'] = $z;
            $z++;

        }
        $errorList = array();
        $emList = array();
        $j = 0;
        $z = 0;
        $dataBaseDao = new BaseDataDao();
        $companyTree = $dataBaseDao->getCompanyRootIdByCompanyId($companyId);
        if(empty($companyTree)){
            $treeJson['data']['company_id'] = $companyId;
            $treeJson['data']['name'] = $company['company_name'];
            $treeJson['data']['pid'] = 0;
            $treeJson['data']['isParent'] = 'true';
            $data = $treeJson['data'];
            $result = $dataBaseDao->addDepartmentTreeData($data);
            $last_id = $dataBaseDao->g_db_last_insert_id();
            $companyTree['id'] = $last_id;
        }

        for ($i = 1; $i <= count($employList); $i++) {
            if ($employList[$i]['e_num']) {
                $emper = $this->objDao->getEmByEno($employList[$i]['e_num']);
                if (!empty($emper)) {
                    $errorList[$j]["errmg"] = "此员工身份证号已存在，请重新确认";
                    $errorList[$j]["e_name"] = $employList[$i]["e_name"];
                    $errorList[$j]["e_num"] = $employList[$i]["e_num"];
                    $j++;
                    continue;
                }
                if (!empty($employList[$i]['depart'])) {
                    $dapartment = trim($employList[$i]['depart']);
                    $dataDpart = $dataBaseDao->getDepartmentByNameAndComId($dapartment,$companyTree['id']);
                    if (!empty($companyTree) && empty($dataDpart)) {
                        $data['company_id'] = 0;
                        $data['name'] = $dapartment;
                        $data['pid'] = $companyTree['id'];
                        $result = $dataBaseDao->addDepartmentTreeData($data);
                        if ($result){
                            $id = $this->objDao->g_db_last_insert_id();
                            $employList[$i]['department_id'] = $id;
                        }
                    } else if($dataDpart['id']){
                        $employList[$i]['department_id'] = $dataDpart['id'];
                    }
                }
                $retult = $this->objDao->addEm($employList[$i]);

                if ($retult) {
                    $emList[$z]['e_num'] = $employList[$i]["e_num"];
                    $emList[$z]['e_name'] = $employList[$i]["e_name"];
                    $z++;
                }
            } else if(!empty($employList[$i]['e_name']) && !$employList[$i]['e_num']){
                $errorList[$j]["errmg"] = "{$employList[$i]['e_num']} :此员工身份证号为空或是系统无法识别，请重新复制到模版重新上传";
                $errorList[$j]["e_name"] = $employList[$i]["e_name"];
                $errorList[$j]["e_num"] = $employList[$i]["e_num"];
                $j++;
            }
        }
        $opLog = array();
        $adminPO = $_SESSION['admin'];
        $opLog['who'] = $adminPO['id'];
        $opLog['what'] = 0;
        $opLog['Subject'] = OP_LOG_IMPORT_EMPLOY;
        $opLog['memo'] = '';
        $rasult = $this->objDao->addOplog($opLog);
        if (!$rasult) {
            $errorList[$j]["errmg"] = "此员工添加失败，请检查格式是否正确后重新导入";
            $errorList[$j]["e_name"] = $errorList[$j]["e_name"];
            $errorList[$j]["e_num"] = $errorList[$j]["e_num"];
        }
        $this->objForm->setFormData("errorlist", $errorList);
        $this->objForm->setFormData("emList", $emList);
    }
    function toEmployList () {
        $this->mode = "toEmployList";
        $searchType = $_REQUEST['searchType'];
        $search_name = $_REQUEST['search_name'];
        $user = $_SESSION ['admin'];
        if ($user['user_type']== 3) {
            $companyId = $user['company_id'];
        } else {
            $companyId = $user['user_id'];
        }

        $this->objDao = new EmployDao();
        $where = '';
        if ($user['user_type']== 3) {
            $where.= ' and department_id='.$user['user_id'];
        }
        $where.= ' and e_company_id='.$companyId;
        if ($searchType =='e_company') {
            $where.= ' and e_company like "%'.$search_name.'%"';
        } elseif ($searchType =='e_num') {
            $where.= ' and e_num like "'.$search_name.'%"';
        } elseif ($searchType =='e_name') {
            $where.= ' and e_name like "%'.$search_name.'%"';
        }
        $sum =$this->objDao->g_db_count("OA_employ","*","1=1 $where");
        $pageSize=PAGE_SIZE_EMPLOY;
        $count = intval($_GET['c']);
        $page = intval($_GET['page']);
        if ($count == 0){
            $count = $pageSize;
        }
        if ($page == 0){
            $page = 1;
        }

        $startIndex = ($page-1)*$count;
        $total = $sum;
        $searchResult=$this->objDao->getEmployList($where,$startIndex,$pageSize);
        $pages = new JPagination($total);
        $pages->setPageSize($pageSize);
        $pages->setCurrent($page);
        $pages->makePages();
        $employList = array();
        global $userType;
        global $employState;
        //company_code,company_name,com_contact,contact_no,company_address,com_bank,bank_no,company_level,company_type,company_status
        while ($row = mysql_fetch_array($searchResult)) {
            $employ['id'] = $row['id'];
            $employ['e_company_id'] = $row['e_company_id'];
            $employ['e_name'] = $row['e_name'];
            $employ['e_company'] = $row['company_name'];
            $employ['e_num'] = $row['e_num'];
            $employ['e_type_name'] = $userType[$row['e_type']];
            $employ['shebaojishu'] = $row['shebaojishu'];
            $employ['gongjijinjishu'] = $row['gongjijinjishu'];
            $employ['e_state_name'] = $employState[$row['e_status']];
            $employList[] = $employ;
        }
        $this->objForm->setFormData("employList",$employList);
        $this->objForm->setFormData("total",$total);
        $this->objForm->setFormData("page",$pages);
        $this->objForm->setFormData("searchType",$searchType);
        $this->objForm->setFormData("search_name",$search_name);
    }
    function saveOrUpdateEmploy () {
        //company_name,com_contact,contact_no,company_address,com_bank,bank_no,company_level,company_type
        $user = $_SESSION ['admin'];
        if ($user['user_type'] == 3) {
            $companyId = $user['company_id'];
            $company_name =  $user['company_name'];
        } elseif ($user['user_type'] == 1) {
            $companyId = $user['user_id'];
            $company_name =  $user['real_name'];
        }
        $employ = array();
        $employ['id'] = $_POST['employ_id'];
        $employ['e_name'] = $_POST['e_name'];
        $employ['e_company_id'] = $companyId;
        $employ['e_num'] = $_POST['e_num'];
        $employ['bank_name'] = $_POST['e_bank'];
        $employ['bank_num'] = $_POST['bank_no'];
        $employ['e_type'] = $_POST['e_type'];
        $employ['e_company'] = $company_name;
        $employ['shebaojishu'] = $_POST['shebaojishu'];
        $employ['gongjijinjishu'] = $_POST['gongjijinjishu'];
        $employ['laowufei'] = $_POST['laowufei'];
        $employ['canbaojin'] = $_POST['canbaojin'];
        $employ['danganfei'] = $_POST['danganfei'];
        $employ['e_hetongnian'] = $_POST['e_hetongnian'];
        $employ['e_hetong_date'] = $_POST['e_hetong_date'];
        $employ['e_status'] = $_POST['e_status'];
        $employ['department_id'] = $_POST['department_id'];
        $employ['memo'] = $_POST['memo'];
        $this->objDao = new EmployDao();
        $data = array();
        if (empty($employ['id'])) {
            $emper = $this->objDao->getEmByEno($employ['e_num']);
            if (!empty($emper)) {
                $mess = "此员工身份证号已存在，请重新确认";
                $data['code'] = 100001;
                $data['mess'] = $mess;
                echo json_encode($data);
                exit;
            }
            $result = $this->objDao->addEm($employ);
            if ($result) {
                $data['code'] = 100000;
                $data['mess'] = '员工添加成功';
            } else {
                $data['code'] = 100001;
                $data['mess'] = '员工添加失败，请重试';
            }
        } else {
            $result = $this->objDao->updateEm($employ);
            if ($result) {
                $data['code'] = 100000;
                $data['mess'] = '员工修改成功';
            } else {
                $data['code'] = 100001;
                $data['mess'] = '员工修改失败，请重试';
            }
        }
        echo json_encode($data);
        exit;

    }

}


$objModel = new EmployAction($actionPath);
$objModel->dispatcher();



?>
