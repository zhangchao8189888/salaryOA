<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class StatisForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function StatisForm()
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
            case "toEmployList":
                return "statis/employList.php";
            case "toChartList":
                return "statis/chartList.php";
            default :
                return "BaseConfig.php";
        }
    }
}
?>

