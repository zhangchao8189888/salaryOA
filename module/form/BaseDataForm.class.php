<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class BaseDataForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function BaseDataForm()
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
            case "toShenfenType" :
                return "baseData/shenfenType_add.php";
            case "toDepartmentEdit" :
                return "baseData/departmentEdit.php";
            case "toSetSalZiduan" :
                return "baseData/setSalZiduan.php";
            default :
                return "BaseConfig.php";
        }
    }
}

