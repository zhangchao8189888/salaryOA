<?php
require_once("common/BaseDao.class.php");
require_once("common/BaseForm.class.php");
class BaseAction
{
    //action路径
    var $actionPath;
    //Form对象
    var $objForm;
    //操作DB对象
    var $objDao;
    //页面模式
    var $mode;
    //页面
    var $pageId;
    //设置管理员属性
    var $admin;
    function BaseAction()
    {
    }
    function initBase()
    {
        //开始SESSION
        //startSession();
        //页面ID设定
        //setPageID($this->actionPath);
        //页面ID取得
        //$this->pageId = getPageID();
    }
    function view()
    {
        // 取得画面表示文件
        $nextPageFile = $this->objForm->getTpl($this->mode);
        // 取得画面表示数据
        $form_data = $this->objForm->getFormData();
        // 画面表示
        require_once("tpl/common/main.php");
        // 画面表示完了、清空SESSION
        //unsetNamespace(getPageID());
        // 对象释放
        unset($this->objForm);
        unset($this->objDao);
    }
    /**
     *关闭数据库
     */
    function closeDB()
    {
        if (isset($this->objDao)) {
            $this->objDao->closeConnect();
        }
    }
    function AssignTabMonth($date,$step){
        $date= date("Y-m-d",strtotime($step." months",strtotime($date)));//得到处理后的日期（得到前后月份的日期）
        $u_date = strtotime($date);
        $days=date("t",$u_date);// 得到结果月份的天数

        //月份第一天的日期
        $first_date=date("Y-m",$u_date).'-01';
        for($i=0;$i<$days;$i++){
            $for_day=date("Y-m-d",strtotime($first_date)+($i*3600*24));
        }
        $time = array ();
        $time["data"]   =  $date ;
        $time["next"]   =   (date("Y-m-d",strtotime("+1 day",strtotime($date))));
        $time["month"]  =   (date("Y-m",strtotime($date)));
        $time["first"]  =    $first_date;
        $time["last"]   =      $for_day;
        $time["days"]   =      $days;
        return $time;
    }
    public function getParam($name,$method='post') {
        if ($method == 'get') {
            $val = $_GET[$name];
        } else {
            $val = $_POST[$name];
        }
        if (is_numeric($val)) {
            $val = $this->get_int($val);
        } else {
            $val = $this->get_str($val);
        }
        return $val;
    }
    //整型过滤函数

    function get_int($number)

    {

        return intval($number);

    }

//字符串型过滤函数

    function get_str($string)

    {

        if (!get_magic_quotes_gpc()) {

            return addslashes($string);

        }

        return $string;

    }

    function logoff(){
        $type=$_SESSION['admin']['user_type'];
        $_SESSION['admin']=NULL;
        if ($type == 2){
            header("Location: index.php?mode=employ");
        } else {
            header("Location: index.php");
        }

    }
    function readExcelContent($fileName) {


    }
    function sumTableHead ($jsonData,$headMap = null) {
        $defultLength = 80;
        $headLenData = array();
        foreach ($jsonData as $key => $val) {
            if (isset($headMap[$key])) {
                $headLenData[$key] = $headMap[$key];
            } else {
                $headLenData[$key] = $defultLength;
            }
        }
        return $headLenData;
    }
    function sumTableHeadByStringLen ($jsonData) {
        $headLenData = array();
        foreach ($jsonData as $key => $val) {
                $headLenData[$key] = strlen($val)*5;
        }
        return $headLenData;
    }

}
?>
