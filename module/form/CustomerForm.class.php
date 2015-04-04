<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class CustomerForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function CustomerForm()
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
            case "toAdd" :
                return "customer/customer_add.php";
            case "toList" :
                return "customer/customer_list.php";
            case "getCust" :
                return "customer/customer_modify.php";
            case "tojingbanrenList":
            	return "customer/customer_manager.php";
            case "toCustomerExport":
            	return "customer/customer_export.php";
            case "toAddCustomerLevel":
            	return "customer/customer_level.php";
            default :
                return "BaseConfig.php";
        }
    }
    }
?>

