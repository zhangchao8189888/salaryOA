<?php
require_once("module/form/StatisForm.class.php");
require_once("module/dao/SalaryDao.class.php");
require_once("module/dao/BaseDataDao.class.php");
require_once("module/dao/EmployDao.class.php");
require_once("tools/excel_class.php");
require_once("tools/Classes/PHPExcel.php");
require_once("tools/Util.php");
require_once("tools/JPagination.php");
require_once("tools/fileTools.php");
require_once ("tools/sumSalary.class.php");

class StatisAction extends BaseAction {
    /*
        *
        * @param $actionPath
        * @return TestAction
        */
    function StatisAction($actionPath) {
        parent::BaseAction();
        $this->objForm = new StatisForm();
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
            case "getEmploySalList" :
                $this->getEmploySalList();
                break;
            case "toChartList" :
                $this->toChartList();
                break;
            default :
                $this->modelInput();
                break;
        }


    }
    function modelInput() {
        $this->mode = "toAdd";
    }
    function toEmployList () {
        $this->mode = "toEmployList";
        session_start();
        $user = $_SESSION ['admin'];
        $company_id = $user['user_id'];
        $this->objDao = new BaseDataDao();
        $department = array();
        if(!empty($company_id)) {

            $companyTree = $this->objDao->getCompanyRootIdByCompanyId($company_id);
            $companyList = $this->objDao->getDepartmentsByCompanyId($companyTree['id']);
            while($row = mysql_fetch_array($companyList)) {
                $pao['name'] = $row['name'];
                $pao['id'] = $row['id'];
                $department[] = $pao;
            }
        }
        $this->objForm->setFormData("department",$department);
    }
    function getEmploySalList () {

        $dList = $_REQUEST['dList'];
        print_r($dList);
        exit;
    }
    function toChartList () {
        $this->mode = "toChartList";
        session_start();
        $user = $_SESSION ['admin'];
        $company_id = $user['user_id'];
        $this->objDao = new BaseDataDao();
        $department = array();
        if(!empty($company_id)) {

            $companyTree = $this->objDao->getCompanyRootIdByCompanyId($company_id);
            $companyList = $this->objDao->getDepartmentsByCompanyId($companyTree['id']);
            while($row = mysql_fetch_array($companyList)) {
                $pao['name'] = $row['name'];
                $pao['id'] = $row['id'];
                $department[] =  $row['name'];
            }
        }
        $this->objForm->setFormData("department",$department);
    }

}


$objModel = new StatisAction($actionPath);
$objModel->dispatcher();



?>
