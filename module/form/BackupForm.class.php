<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class BackupForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function BackupForm()
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
            case "toBackUp" :
                return "backup/backup.php";
           default :
                return "BaseConfig.php";
        }
    }
}
?>

