<?php
require_once("module/form/BaseDataForm.class.php");
require_once("module/dao/CompanyDao.class.php");
require_once("module/dao/BaseDataDao.class.php");
require_once("tools/excel_class.php");
require_once("tools/Classes/PHPExcel.php");
require_once("tools/Util.php");
require_once("tools/JPagination.php");

class BaseDataAction extends BaseAction {
    /*
        *
        * @param $actionPath
        * @return TestAction
        */
    function BaseDataAction($actionPath) {
        parent::BaseAction();
        $this->objForm = new BaseDataForm();
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
            case "toShenfenType" :
                $this->toShenfenType();
                break;
            case "saveOrUpdateShenfenType" :
                $this->saveOrUpdateShenfenType();
                break;
            case "deleteShenfen" :
                $this->deleteShenfen();
                break;
            case "toDepartmentEdit" :
                $this->toDepartmentEdit();
                break;
            case "getDepartmentTreeJson" :
                $this->getDepartmentTreeJson();
                break;
            case "addDepartmentTreeJson" :
                $this->addDepartmentTreeJson();
                break;
            case "addEmployTreeJson" :
                $this->addEmployTreeJson();
                break;
            case "editDepartmentTreeJson" :
                $this->editDepartmentTreeJson();
                break;
            case "delDepartmentTreeJson" :
                $this->delDepartmentTreeJson();
                break;
            case "getEmployJson" :
                $this->getEmployJson();
                break;
            case "getEmployByIdJson" :
                $this->getEmployByIdJson();
                break;
            case "toEmployList" :
                $this->toEmployList();
                break;
            case "getDepartmentByComId" :
                $this->getDepartmentByComId();
                break;
            case "toSetSalZiduan" :
                $this->toSetSalZiduan();
                break;
            default :
                $this->modelInput();
                break;
        }


    }

    function modelInput() {
        $this->mode = "toAdd";
    }
    function toSetSalZiduan () {
        $this->mode = "toSetSalZiduan";
    }
    function toShenfenType () {
        $this->mode = "toShenfenType";
        $this->objDao = new BaseDataDao();
        $result = $this->objDao->getShenfenTypeList();
        $shenfenList = array();
        while($row = mysql_fetch_array($result)) {
            $shenfenList[] = $row ;
        }
        $this->objForm->setFormData("shenfenList",$shenfenList);
    }
    function deleteShenfen () {
        $id = $_POST['id'];
        $this->objDao = new BaseDataDao();
        $result = $this->objDao->deleteShenfen($id);
        if ($result) {
            $data['code'] = 100000;
        } else {
            $data['code'] = 100001;
            $data['mess'] = '删除失败，请重试';
        }
        echo json_encode($data);
        exit;
    }
    function saveOrUpdateShenfenType () {
        $typeName = $_POST['shenfenType'];
        $type_id = $_POST['type_id'];
        $id = $_POST['id'];
        $adminPO = $_SESSION ['admin'];
        $this->objDao = new BaseDataDao();
        $type = array();
        $type['type_name'] = $typeName;
        $type['op_id'] = $adminPO['id'];
        $type['type_id'] = $type_id;
        $type['id'] = $id;

        if (empty($type['id'])) {
            $typePo = $this->objDao->getShenfenDataByName($type['type_name']);
            if (!empty($typePo)) {
                $data['code'] = 100001;
                $data['mess'] = '已经添加过了';
                echo json_encode($data);
                exit;
            }
            $result = $this->objDao->addShenfenData($type);
            if ($result) {
                $data['code'] = 100000;
                $data['mess'] = '身份类别添加成功';
            } else {
                $data['code'] = 100001;
                $data['mess'] = '身份类别添加失败，请重试';
            }
        } else {
            $result = $this->objDao->updateShenfenData($type);
            if ($result) {
                $data['code'] = 100000;
                $data['mess'] = '身份类别修改成功';
            } else {
                $data['code'] = 100001;
                $data['mess'] = '身份类别修改失败，请重试';
            }
        }
        echo json_encode($data);
        exit;
    }
    function toDepartmentEdit () {
        $this->mode="toDepartmentEdit";
    }
    function editDepartmentTreeJson () {
        //$companyId =  $_POST['companyId'];
        $companyId =  2;
        $id = $_POST['id'];
        $name = $_POST['name'];
        $data['company_id'] = $companyId;
        $data['name'] = $name;
        $data['id'] = $id;
        $this->objDao = new BaseDataDao();
        $result = $this->objDao->eitDepartmentTreeData($data);
        $megs = array();
        if ($result){
            $megs['code'] =10000;
        } else {
            $megs['code'] =10002;
        }
        echo json_encode($megs);
        exit;

    }
    function delDepartmentTreeJson () {
        $id = $_POST['id'];
        $data['id'] = $id;
        $this->objDao = new BaseDataDao();
        $result = $this->objDao->delDepartmentTreeData($data);
        $megs = array();
        if ($result){
            $megs['code'] =10000;
        } else {
            $megs['code'] =10002;
        }
        echo json_encode($megs);
        exit;

    }
    function getEmployJson () {
        $this->objDao = new BaseDataDao();
        $companyName ="系统测试公司";
        $result = $this->objDao->getEmlistbyComname($companyName);
        $emArr = array();
        $i=0;
        while ($row = mysql_fetch_array($result)) {
            $emArr[$i] ['id']= $row['id'];
            $emArr[$i] ['eName']= $row['e_name'];
            $emArr[$i] ['eNo']= $row['e_num'];
            $i++;
        }
        echo json_encode($emArr);
        exit;
    }
    function getEmployByIdJson () {
        $id = $_POST['id'];
        $this->objDao = new BaseDataDao();
        //$companyName ="系统测试公司";
        $result = $this->objDao->getEmployById($id);
        echo json_encode($result);
        exit;
    }
    function getDepartmentByComId () {
        $company_id = $_REQUEST['companyId'];
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
        echo json_encode($department);
        exit;
    }
    function getDepartmentTreeJson() {
       $company_id = $_REQUEST['comapny_id'];
//        $companyName ="测试单位";
        $id = $_POST['id'];
        $this->objDao = new BaseDataDao();
        $treeJson =array();
        if(empty($id)) {

            $companyList = $this->objDao->searchCompanyListAll();
            $i = 0;
            while ($row = mysql_fetch_array($companyList)) {
                $companyId = $row['id'];
                $companyPo = $this->objDao->getCompanyRootIdByCompanyId($row['id']);
                if ($companyPo) {
                    $treeJson['data'][$i]['id'] = $companyPo['id'];
                    $treeJson['data'][$i]['name'] = $companyPo['name'];
                    $treeJson['data'][$i]['pid'] = $companyPo['pid'];
                    $treeJson['data'][$i]['company_id'] = $row['id'];
                    $count = $this->objDao->isParentNode($companyId,$companyPo['id']);
                    if ($count['cnt'] > 0) {
                        $treeJson['data'][$i]['isParent'] = 'true';
                    } else {
                        $treeJson['data'][$i]['isParent'] = 'false';
                    }
                    $treeJson['data'][$i]['isParent'] = 'true';

                } else {
                    $treeJson['data'][$i]['company_id'] = $companyId;
                    $treeJson['data'][$i]['name'] = $row['company_name'];
                    $treeJson['data'][$i]['pid'] = 0;
                    $treeJson['data'][$i]['isParent'] = 'true';
                    $data = $treeJson['data'][$i];
                    $result = $this->objDao->addDepartmentTreeData($data);
                    $last_id = $this->objDao->g_db_last_insert_id();
                    $treeJson['data'][$i]['id'] = $last_id;
                }
                $i++;
            }


        } else {
            //找到树节点

            $treeNode = $this->objDao->getTreeNodeDataById($id);
            if ($treeNode) {
                $result = $this->objDao->getChildNodeDataByPid($treeNode['id']);
                $i = 0;
                while($row = mysql_fetch_array($result)) {
                    $treeJson['data'][$i]['id'] = $row['id'];
                    $treeJson['data'][$i]['name'] = $row['name'];
                    $treeJson['data'][$i]['pid'] = $row['pid'];
                    $treeJson['data'][$i]['employ_id'] = $row['employ_id'];
                    $treeJson['data'][$i]['is_employ'] = $row['is_employ'];
                    $count = $this->objDao->isParentNode($row['id']);
                    if ($count['cnt'] > 0) {
                        $treeJson['data'][$i]['isParent'] = 'true';
                    } else {
                        $treeJson['data'][$i]['isParent'] = 'false';
                    }
                    $i++;
                }
            } else {
                echo '{}';
                exit;
            }

        }
        echo json_encode($treeJson);
        exit;

    }
    function addDepartmentTreeJson() {
        $pid = $_POST['id'];
        $name = $_POST['name'];
        $data['company_id'] = 0;
        $data['name'] = $name;
        $data['pid'] = $pid;
        $this->objDao = new BaseDataDao();
        $result = $this->objDao->addDepartmentTreeData($data);
        $megs = array();
        if ($result){
            $id = $this->objDao->g_db_last_insert_id();
            $treeNode = $this->objDao->getTreeNodeDataById($id);
            $treeNode['isParent'] = 'true';
            $megs['data']['id'] = $treeNode['id'];
            $megs['data']['name'] = $treeNode['name'];
            $megs['data']['pid'] = $treeNode['pid'];
            $megs['data']['isParent'] = 'false';
            $megs['code'] =10000;
        } else {
            $megs['code'] =10002;
        }
        echo json_encode($megs);
        exit;
    }
    function addEmployTreeJson() {
        $companyId = 11;
        $pid = $_POST['id'];
        $ids = trim($_POST['ids'],',');

        $idArr = explode(",",$ids);
        $this->objDao = new BaseDataDao();
        foreach ($idArr as $id){
            $data['company_id'] = $companyId;
            $employ = $this->objDao->getEmployById($id);
            $data['name'] = $employ['e_name'];
            $data['pid'] = $pid;
            $data['employ_id'] = $id;

            $result = $this->objDao->addEmployTreeData($data);
        }
        $megs['code'] =10000;
        echo json_encode($megs);
        exit;
    }
    function toEmployList () {
        $this->mode = 'toEmployList';
        $this->objDao = new BaseDataDao();
        $companyName ="系统测试公司";
        $result = $this->objDao->getEmlistbyComname($companyName);
        $emArr = array();
        $i=0;
        while ($row = mysql_fetch_array($result)) {
            $daoqiDate=date('Y-m-d', strtotime($row['e_hetong_date']."{$row['e_hetongnian']}year"));
            $emArr[$i] =$row;
            $emArr[$i]['daoqiri'] = $daoqiDate;
            $i++;
        }
        $this->objForm->setFormData("emList",$emArr);
    }
}


$objModel = new BaseDataAction($actionPath);
$objModel->dispatcher();



?>
