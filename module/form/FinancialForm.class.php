<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class FinancialForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function FinancialForm()
    {
        //页面formData做成
        parent::BaseForm();
    }
    /**
     * 取得tpl文件
     *
     * @param $mode　模式
     * @return 页面表示文件
     */
    function getTpl($mode = false)
    {
        switch ($mode) {
            case "toShoukuanList":
                return "salary/shoukuan_list.php";
            case "toFukuanList":
                return "salary/fukuan_list.php";
            case "toFukuandanList":
                return "salary/fukuandan_list.php";
            default :
                return "BaseConfig.php";
        }
    }
}
?>

