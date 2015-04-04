<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class EmployForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function EmployForm()
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
            case "toEmployList" :
                return "employ/employList.php";
            case "toEmImport" :
                return "employ/employ_import.php";
            default :
                return "BaseConfig.php";
        }
    }
}
?>

