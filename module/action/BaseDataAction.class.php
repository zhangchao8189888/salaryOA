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
            case "getEmployByDeparIdJson" :
                $this->getEmployByDeparIdJson();
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
            case "getZiduanByIdJson" :
                $this->getZiduanByIdJson();
                break;
            case "addZiduan" :
                $this->addZiduan();
                break;
            case "delZiduan" :
                $this->delZiduan();
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
        $user = $_SESSION ['admin'];
        if ($user['user_type'] == 3) {
            $companyId = $user['company_id'];
            $departId = $user['user_id'];
        } elseif ($user['user_type'] == 1) {
            $companyId = $user['user_id'];
            $departId = 0;
        }

        $this->objDao = new BaseDataDao();
        $result = $this->objDao->getZiduanListByComId($companyId,$departId);
        $data = array();
        global $ziduanTupe;
        while($row = mysql_fetch_array($result)) {
            $ziduan = array();
            $ziduan['id'] =  $row['id'];
            $ziduan['zd_name'] =  $row['zd_name'];
            $ziduan['department_id'] =  $row['department_id'];
            $ziduan['company_id'] =  $row['company_id'];
            $ziduan['zd_type'] =  $ziduanTupe[$row['zd_type']];
            $data[] = $ziduan;
        }
        $this->objForm->setFormData("ziduanList",$data);
    }
    function delZiduan () {
        $id = $_REQUEST['zid'];
        $this->objDao = new BaseDataDao();
        $result = $this->objDao->delSalZiduan($id);
        if ($result) {
            $data['code'] = 100000;
        } else {
            $data['code'] = 100001;
            $data['mess'] = '删除失败，请重试';
        }
        echo json_encode($data);
        exit;
    }
    function addZiduan (){
        $user = $_SESSION ['admin'];
        if ($user['user_type'] == 3) {
            $companyId = $user['company_id'];
            $departId = $user['user_id'];
        } elseif ($user['user_type'] == 1) {
            $companyId = $user['user_id'];
            $departId = 0;
        }
        $zd = array();
        $zd['zd_name'] = $_REQUEST['zd_name'];
        $zd['zd_type'] = $_REQUEST['zd_type'];
        $zd['department_id'] = $departId;
        $zd['company_id'] = $companyId;

        $this->objDao = new BaseDataDao();
        $ziduan = $this->objDao->getZiduanListByName($zd['zd_name'],$companyId);
        if ($ziduan) {
            $data['code'] = 100001;
            $data['mess'] = '该字段名称已经存在';
        }else {
            $result = $this->objDao->addSalZiduan($zd);
            if ($result) {
                $data['code'] = 100000;
                $data['mess'] = $this->objDao->g_db_last_insert_id();
            } else {
                $data['code'] = 100001;
                $data['mess'] = '添加失败，请重试';
            }
        }

        echo json_encode($data);
        exit;
    }
    function getZiduanByIdJson () {
        $user = $_SESSION ['admin'];
        if ($user['user_type'] == 3) {
            $companyId = $user['company_id'];
            $departId = $user['user_id'];
        } elseif ($user['user_type'] == 1) {
            $companyId = $user['user_id'];
            $departId = 0;
        }
        $this->objDao = new BaseDataDao();
        $result = $this->objDao->getZiduanListByComAndDepartmentId($companyId,$departId);
        $data = array();
        global $ziduanTupe;
        while($row = mysql_fetch_array($result)) {
            $ziduan = array();
            $ziduan['id'] =  $row['id'];
            $ziduan['zd_name'] =  $row['zd_name'];
            $ziduan['department_id'] =  $row['department_id'];
            $ziduan['company_id'] =  $row['company_id'];
            $ziduan['zd_type'] =  $ziduanTupe[$row['zd_type']];
            $data[] = $ziduan;
        }
        echo json_encode($data);
        exit;
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
    function getEmployByDeparIdJson () {
        $id = $_POST['id'];
        $this->objDao = new BaseDataDao();
        $result = $this->objDao->getEmlistbyDepartId($id);
        $emArr = array();
        $i=0;
        global $userType;
        while ($row = mysql_fetch_array($result)) {
            $emArr[$i] ['id']= $row['id'];
            $emArr[$i] ['e_name']= $row['e_name'];
            $emArr[$i] ['e_num']= $row['e_num'];
            $emArr[$i] ['e_type'] = $userType[$row['e_type']];
            $emArr[$i] ['e_hetong_start'] = $row['e_hetong_date'];
            $emArr[$i] ['e_hetong_end'] = date('Y-m-d',strtotime('+'.$row['e_hetongnian'].' year',strtotime($row['e_hetong_date'])));
            $i++;
        }
        $data['data'] = $emArr;
        echo json_encode($data);
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
    function getDepartmentTreeJson () {
        $user = $_SESSION ['admin'];
        $company_id = $user['user_id'];
        $id = $_POST['id'];
        $this->objDao = new BaseDataDao();
        $treeJson =array();
        if(empty($id)) {
            $company = $this->objDao->getCompanyById($company_id);
            $companyId = $company_id;
            $companyPo = $this->objDao->getCompanyRootIdByCompanyId($companyId);
            if ($companyPo) {
                $treeJson['data']['id'] = $companyPo['id'];
                $treeJson['data']['name'] = $companyPo['name'];
                $treeJson['data']['pid'] = $companyPo['pid'];
                $treeJson['data']['company_id'] = $companyId;
                $count = $this->objDao->isParentNode($companyId,$companyPo['id']);
                if ($count['cnt'] > 0) {
                    $treeJson['data']['isParent'] = 'true';
                } else {
                    $treeJson['data']['isParent'] = 'false';
                }
                $treeJson['data']['isParent'] = 'true';
            } else{
                $treeJson['data']['company_id'] = $companyId;
                $treeJson['data']['name'] = $company['company_name'];
                $treeJson['data']['pid'] = 0;
                $treeJson['data']['isParent'] = 'true';
                $data = $treeJson['data'];
                $result = $this->objDao->addDepartmentTreeData($data);
                $last_id = $this->objDao->g_db_last_insert_id();
                $treeJson['data']['id'] = $last_id;

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
    function getDepartmentTreeJsonbak() {
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
