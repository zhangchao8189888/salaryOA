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
            case "getEmpSalList" :
                $this->getEmpSalList();
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
    function getEmpSalList() {
        $this->mode = "toEmployList";
        /**部门查找员工**/
        $departId = $_REQUEST['departId'];
        $startDate = $_REQUEST['startDate'];
        $endDate = $_REQUEST['endDate'];
        $this->objDao = new BaseDataDao();
        $salDao = new SalaryDao();
        $colHeaders = array();
        $colWidths = array();
        $mergeCells = array();
        $rowData = array();
        $colHeaders[0] = '部门名称';
        $colHeaders[1] = '员工名称';
        $colWidths[0] = 160;
        $colWidths[1] = 100;

        session_start();
        $user = $_SESSION ['admin'];
        $company_id = $user['user_id'];
        $result = $salDao->searchSalTimeBySalTime($company_id,$startDate,$endDate);
        $salTimeList = array();
        while ($row =  mysql_fetch_array($result)) {
            $colHeaders[] = $row['salaryTime'];
            $colWidths[] = 100;
            $salTimeList[] = $row;
        }
        $row_num = 0;
        foreach($departId as $val){
            $employList = $this->objDao->getEmlistbyDepartId($val);
            $depart = $this->objDao->getDepartmentsById($val);
            $count = $this->objDao->getCountEmlistbyDepartId($val);

            $mergeCells[] = array(
                'row'=> $row_num, 'col'=> 0, 'rowspan'=> intval($count), 'colspan'=> 1
            );

            while ($row = mysql_fetch_array($employList)) {
                $rowData[$row_num][0] = $depart['name'];
                $rowData[$row_num][1] = $row['e_name'];
                $salI = 2;
                foreach ($salTimeList as $salTime) {
                    $salResult = $salDao->getSalListByEmpNum($row['e_num'],$salTime['id']);

                    $rowData[$row_num][$salI] =$salResult['per_shifaheji'];
                    $salI++;
                }
                $row_num++;
            }
        }
        /**
         * $colHeaders = array();
        $colWidths = array();
        $mergeCells = array();
        $data = array();
         */
        $data['colHeaders'] = $colHeaders;
        $data['colWidths'] = $colWidths;
        $data['mergeCells'] = $mergeCells;
        $data['rowData'] = $rowData;
        echo json_encode($data);
        exit;

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
        $salaryDao = new SalaryDao();

        $salTime = $_POST['salaryDate'];
        if(empty($salTime)){
            $salTime = date("Y-m");
        }
        $salTimePo = $salaryDao->searchSalTimeByComIdAndSalTime($company_id,$salTime.'-01',$salTime,1);

        $department = array();
        $per_shifaheji = array();
        $per_daikoushui = array();
        $com_heji = array();
        if(!empty($company_id)) {

            $companyTree = $this->objDao->getCompanyRootIdByCompanyId($company_id);
            $companyList = $this->objDao->getDepartmentsByCompanyId($companyTree['id']);
            while($row = mysql_fetch_array($companyList)) {
                $pao['name'] = $row['name'];
                $pao['id'] = $row['id'];
                $department[] =  $row['name'];
                $salPo = $salaryDao->getSumSalByDeaprtId($salTimePo['id'],$row['id']);
                $per_shifaheji[] = $salPo['per_shifaheji'];
                $per_daikoushui[] = $salPo['per_daikoushui'];
                $com_heji[] = $salPo['com_heji'];
            }
        }
        $this->objForm->setFormData("department",$department);
        $this->objForm->setFormData("per_shifaheji",$per_shifaheji);
        $this->objForm->setFormData("per_daikoushui",$per_daikoushui);
        $this->objForm->setFormData("com_heji",$com_heji);
        $this->objForm->setFormData("salTime",$salTime);
    }

}


$objModel = new StatisAction($actionPath);
$objModel->dispatcher();



?>
