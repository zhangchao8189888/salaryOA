<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class CompanyForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function CompanyForm()
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
            case "toCompanyList" :
                return "company/company_list.php";
            case "toTest" :
                return "company/test.php";
            default :
                return "BaseConfig.php";
        }
    }
}
?>

