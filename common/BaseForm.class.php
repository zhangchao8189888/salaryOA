<?php
class BaseForm
{
 /**
     * 表示数据
     *
     * @var array
     */
     var $formData;

    /**
     *
     * @return BaseForm
     */
    function BaseForm()
    {
    }

    /**
     * 数据设定
     *
     * @param  $key 
     * @param  $value 
     */
    function setFormData($key,$value)
    {
        if ($key != "") {
            $this->formData[$key] = $value;
        }
    }

    /**
     * 数据取得
     *
     * @return formData
     */
    function getFormData()
    {
        if (!is_array($this->formData)) {
            return "";
        }
        return $this->formData;
    }
	
}

?>
