<?php
require_once("module/form/CompanyForm.class.php");
require_once("module/dao/CompanyDao.class.php");
require_once("module/dao/SalaryDao.class.php");
require_once("module/dao/BaseDataDao.class.php");
require_once("tools/excel_class.php");
require_once("tools/Classes/PHPExcel.php");
require_once("tools/Util.php");
require_once("tools/JPagination.php");

class CompanyAction extends BaseAction {
    /*
        *
        * @param $actionPath
        * @return TestAction
        */
    function CompanyAction($actionPath) {
        parent::BaseAction();
        $this->objForm = new CompanyForm();
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
            case "toCompanyList" :
                $this->toCompanyList();
                break;
            case "demoTest" :
                $this->demoTest();
                break;
            case "saveOrUpdateCompany" :
                $this->saveOrUpdateCompany();
                break;
            case "getCode" :
                $this->getCode();
                break;
            case "getCompany" :
                $this->getCompany();
                break;
            case "verifyCompanyName" :
                $this->verifyCompanyName();
                break;
            case "getCompanyListJson" :
                $this->getCompanyListJson();
                break;
            case "getCompanyByIdJson" :
                $this->getCompanyByIdJson();
                break;
            default :
                $this->modelInput();
                break;
        }


    }

    function modelInput() {
        $this->mode = "toAdd";
    }
    function demoTest () {
        $this->mode = "toTest";
    }
    function toCompanyList () {
        $this->mode = "toCompanyList";
        $user = $_SESSION ['admin'];
        $companyId = $user['user_id'];
        $this->objDao = new CompanyDao();

        $where = ' and id ='.$companyId;

        $sum =$this->objDao->g_db_count("OA_company","*","1=1 $where");
        $pageSize=PAGE_SIZE;
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
        $searchResult=$this->objDao->getCompanyList($where,$startIndex,$pageSize);
        $pages = new JPagination($total);
        $pages->setPageSize($pageSize);
        $pages->setCurrent($page);
        $pages->makePages();
        $companyList = array();
        //company_code,company_name,com_contact,contact_no,company_address,com_bank,bank_no,company_level,company_type,company_status
        global $companyType;
        global $companyLevel;
        while ($row = mysql_fetch_array($searchResult)) {
            $company['id'] = $row['id'];
            $company['company_code'] = $row['company_code'];
            $company['company_name'] = $row['company_name'];
            $company['com_contact'] = $row['com_contact'];
            $company['contact_no'] = $row['contact_no'];
            $company['company_address'] = $row['company_address'];
            $company['com_bank'] = $row['com_bank'];
            $company['bank_no'] = $row['bank_no'];
            $company['company_level'] = $companyLevel[$row['company_level']];
            $company['company_type'] = $companyType[$row['company_type']];
            $company['company_status'] = $row['company_status'];
            $companyList[] = $company;
        }
        $this->objForm->setFormData("companyList",$companyList);
        $this->objForm->setFormData("total",$total);
        $this->objForm->setFormData("page",$pages);

    }
    function getCode() {
        $type = $_GET['type'];
        $firstCode = '';
        $table ='';
        if ($type =='qiye') {
            $firstCode = 'QY';
            $table = 'OA_company';
        } elseif ($type =='fukuantongzhi') {
            $firstCode = 'PN';
            $table = 'OA_fukuantongzhi';
        } elseif ($type =='shoukuan') {
            $firstCode = 'SK';
            $table = 'OA_shoukuan';
        }
        $date = date("Ymd",time());
        $this->objDao = new CompanyDao();
        $maxId = $this->objDao->getMaxId($table);
        if(empty($maxId['max'])){
            $maxId = 0;
        } else {
            $maxId = $maxId['max'];
        }
        $maxId+=1;
        $maxId =  str_pad($maxId,4,'0',STR_PAD_LEFT);
        $codeNo = $firstCode.$date.$maxId;
        $data['codeNo'] = $codeNo;
        echo json_encode($data);
        exit;
    }
    function getCompany() {
        $id = $_REQUEST['id'];
        $this->objDao = new CompanyDao();
        $result = $this->objDao->getCompanyById($id);
        echo json_encode($result);
        exit;
    }
    function verifyCompanyName () {
        $comName = $_REQUEST['comName'];
        if (empty($comName)){
            echo 'false';
            exit;
        }
        $this->objDao = new CompanyDao();
        $result = $this->objDao->getCompanyByName($comName);
        if($result) echo 'false';
        else{ echo 'true' ;}
        exit;
    }
    function getCompanyListJson () {

        $type=$_REQUEST['type'];
        $keyword=$_REQUEST['keyword'];
        $user = $_SESSION ['admin'];
        $company_id = $user['user_id'];
        $this->objDao = new BaseDataDao();
        $companyPo = $this->objDao->getCompanyRootIdByCompanyId($company_id);
        $this->objDao = new CompanyDao();
        $where = ' where 1=1 and pid='.$companyPo['id'];
        if ($type != 'all'){
            $where.="and name like '%{$keyword}%'";
        }
        //$result = $this->objDao->getCompanyListAll($where);

        $result = $this->objDao->getDepartmentAll($where);
        $customerList = array();
        while ($row = mysql_fetch_array($result)){
            //$row['name'] = $row['company_code'].' '.$row['company_name'];
            $row['name'] = $row['name'];
            $customerList[] = $row;
        }
        echo json_encode($customerList);
        exit;
    }
    function getCompanyByIdJson () {

    }
    function saveOrUpdateCompany () {
        //company_name,com_contact,contact_no,company_address,com_bank,bank_no,company_level,company_type
        $company['company_code'] = $_REQUEST['company_code'];
        $company['company_name'] = $_REQUEST['company_name'];
        $company['com_contact'] = $_REQUEST['contacts'];
        $company['contact_no'] = $_REQUEST['contacts_no'];
        $company['company_address'] = $_REQUEST['com_address'];
        $company['com_bank'] = $_REQUEST['com_bank'];
        $company['bank_no'] = $_REQUEST['bank_no'];
        $company['company_level'] = $_REQUEST['company_level'];
        $company['company_type'] = $_REQUEST['company_type'];
        $company['id'] = $_REQUEST['company_id'];
        $company['company_status'] = $_REQUEST['company_status'];
        $this->objDao = new CompanyDao();
        $data = array();
        if (empty($company['id'])) {
            $result = $this->objDao->addCompany($company);

            if ($result) {
                $id = $this->objDao->g_db_last_insert_id();
                $data['company_id'] = $id;
                $data['name'] = $company['company_name'];
                $data['pid'] = 0;
                $data['isParent'] = 'true';
                $this->objDao = new BaseDataDao();
                $result = $this->objDao->addDepartmentTreeData($data);
                $data['code'] = 100000;
                $data['mess'] = '公司添加成功';
            } else {
                $data['code'] = 100001;
                $data['mess'] = '公司添加失败，请重试';
            }
        } else {

            $result = $this->objDao->updateCompany($company);
            if ($result) {
                $data['code'] = 100000;
                $data['mess'] = '公司修改成功';
            } else {
                $data['code'] = 100001;
                $data['mess'] = '公司修改失败，请重试';
            }
        }
        echo json_encode($data);
        exit;

    }

}


$objModel = new CompanyAction($actionPath);
$objModel->dispatcher();



?>
