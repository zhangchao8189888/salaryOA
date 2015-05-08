<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class AdminForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function AdminForm()
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
            case "toIndex" :
                return "site_index.php";
            case "tolist" :
                return "admin_list.php";
            case "login" :
                return "login.php";
            case "toOpLog" :
                return "log_list.php";
            case "service":
            	return "service/service_frist.php";
            case "toFinance":
            	return "finance/finance_first.php";
            case "toUpload" :
                return "upload.php";
            case "excelList" :
                return "excelHtmlList.php";
            case "afterImportPage" :
                return "afterImport.php";
            case "toUpdate" :
                return "product/productUpdate.php";
            case "index" :
                return "admin/index.php";
            case "test" :
                return "salary/test.php";
           default :
                return "BaseConfig.php";
        }
    }
    }
?>

