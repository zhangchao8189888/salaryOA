<?php
require_once("module/form/FinancialForm.class.php");
require_once("module/dao/SalaryDao.class.php");
require_once("module/dao/EmployDao.class.php");
require_once("tools/excel_class.php");
require_once("tools/Classes/PHPExcel.php");
require_once("tools/Util.php");
require_once("tools/JPagination.php");
require_once("tools/fileTools.php");
require_once ("tools/sumSalary.class.php");

class FinancialAction extends BaseAction {
    /*
        *
        * @param $actionPath
        * @return TestAction
        */
    function FinancialAction($actionPath) {
        parent::BaseAction();
        $this->objForm = new FinancialForm();
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
            case "toShoukuanList" :
                $this->toShoukuanList();
                break;
            case "toFukuanList" :
                $this->toFukuanList();
                break;
            case "toFukuandanList" :
                $this->toFukuandanList ();
                break;
            default :
                $this->modelInput();
                break;
        }


    }
    function modelInput() {
        $this->mode = "toAdd";
    }
    function toFukuandanList () {
        $this->mode = "toFukuandanList";
        $searchType = $_REQUEST['searchType'];
        $com_status = $_REQUEST['com_status'];
        $search_name = $_REQUEST['search_name'];
        $this->objDao = new SalaryDao();
        $where = '';
        if ($searchType =='name') {
            $where['company_name'] = $search_name;
        }
        $sum =$this->objDao->getFukuandanListCount($where);
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
        $searchResult=$this->objDao->getFukuandanList($where,$startIndex,$pageSize);
        $pages = new JPagination($total);
        $pages->setPageSize($pageSize);
        $pages->setCurrent($page);
        $pages->makePages();
        $shoukuanList = array();

        while ($row = mysql_fetch_array($searchResult)) {
            $shoukuanList[] = $row;
        }
        $this->objForm->setFormData("shoukuanList",$shoukuanList);
        $this->objForm->setFormData("total",$total);
        $this->objForm->setFormData("page",$pages);
        $this->objForm->setFormData("searchType",$searchType);
        $this->objForm->setFormData("search_name",$search_name);
        $this->objForm->setFormData("com_status",$com_status);
    }
    function toFukuanList () {
        $this->mode = "toFukuanList";
        $searchType = $_REQUEST['searchType'];
        $com_status = $_REQUEST['com_status'];
        $search_name = $_REQUEST['search_name'];
        $this->objDao = new SalaryDao();
        $where = '';
        if ($searchType =='name') {
            $where.= ' and company_name = "'.$search_name.'"';
        } elseif ($searchType =='status') {
            $where.= ' and company_status ='.$com_status;
        }
        $sum =$this->objDao->g_db_count("OA_fukuantongzhi","*","1=1 $where");
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
        $searchResult=$this->objDao->getFukuantongzhiList($where,$startIndex,$pageSize);
        $pages = new JPagination($total);
        $pages->setPageSize($pageSize);
        $pages->setCurrent($page);
        $pages->makePages();
        $fukuanList = array();
        //company_code,company_name,com_contact,contact_no,company_address,com_bank,bank_no,company_level,company_type,company_status
        while ($row = mysql_fetch_array($searchResult)) {
            $fukuanList[] = $row;
        }
        $this->objForm->setFormData("fukuanList",$fukuanList);
        $this->objForm->setFormData("total",$total);
        $this->objForm->setFormData("page",$pages);
        $this->objForm->setFormData("searchType",$searchType);
        $this->objForm->setFormData("search_name",$search_name);
        $this->objForm->setFormData("com_status",$com_status);
    }
    function toShoukuanList () {
        $this->mode = "toShoukuanList";
        $searchType = $_REQUEST['searchType'];
        $com_status = $_REQUEST['com_status'];
        $search_name = $_REQUEST['search_name'];
        $this->objDao = new SalaryDao();
        $where = '';
        if ($searchType =='name') {
            $where.= ' and company_name = "'.$search_name.'"';
        } elseif ($searchType =='status') {
            $where.= ' and company_status ='.$com_status;
        }
        $sum =$this->objDao->g_db_count("OA_shoukuan","*","1=1 $where");
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
        $searchResult=$this->objDao->getShoukuanList($where,$startIndex,$pageSize);
        $pages = new JPagination($total);
        $pages->setPageSize($pageSize);
        $pages->setCurrent($page);
        $pages->makePages();
        $shoukuanList = array();
        //company_code,company_name,com_contact,contact_no,company_address,com_bank,bank_no,company_level,company_type,company_status
        global $payType;
        while ($row = mysql_fetch_array($searchResult)) {
            $row['pay_type_name'] =$payType[$row['pay_type']];
            $shoukuanList[] = $row;
        }
        $this->objForm->setFormData("shoukuanList",$shoukuanList);
        $this->objForm->setFormData("total",$total);
        $this->objForm->setFormData("page",$pages);
        $this->objForm->setFormData("searchType",$searchType);
        $this->objForm->setFormData("search_name",$search_name);
        $this->objForm->setFormData("com_status",$com_status);
    }

}


$objModel = new FinancialAction($actionPath);
$objModel->dispatcher();



?>
