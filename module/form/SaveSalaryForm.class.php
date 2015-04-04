<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class SaveSalaryForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function SaveSalaryForm()
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
            case "toSaveOk" :
                return "ok.php";
            case "salaryTimeList" :
                return "salaryTime.php";
            case "sa":
            	return "sumSalaryList.php";
            case "salaryNianTimeList" :
                return "nianSalaryTime.php";
            case "salaryList" :
                return "salaryList.php";
            case "nianSalaryList" :
                return "nianSalaryList.php";
            case "erSalaryList" :
                return "erSalaryList.php";
            case "salaryErTimeList" :
                return "erSalaryTime.php";
            case "toSalComlist" :
                return "importSal.php";
            case "toCaiwuImport" :
                return "finance/caiWuImport.php";
            case "toServiceComlist" :
                return "service/serviceComList.php";
                default:
                    echo("e");
                	return "salaryTime.php";
        }
    }
    }
?>

