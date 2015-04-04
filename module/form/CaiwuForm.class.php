<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class CaiwuForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function CaiwuForm()
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
            case "toCaiwuExcel" :
                return "caiwu/caiwu_importExcel.php";
            case "toCaiwu" :
                return "caiwu/caiwu_add.php";
            case "toCaiwuBackup":
            	return "caiwu/caiwu_backup.php";
            case "toUpload":
            	return "caiwu/caiwu_backup.php";
            case "excelList" :
                return "caiwu/excelHtml.php";
            case "afterImportPage" :
                return "afterImport.php";
            case "toCaiwuDele":
            	return "caiwu/caiwu_delete.php";
            case "toCaiwuReturn":
            	return "caiwu/caiwu_return.php";
            default :
                return "BaseConfig.php";
        }
    }
    }
?>

