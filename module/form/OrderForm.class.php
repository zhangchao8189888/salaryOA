<?php
/**
 * 管理员Form
 * @author zhang.chao
 *
 */
class OrderForm extends BaseForm
{
    /**
     *
     * @return AdminForm
     */
    function OrderForm()
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
                return "order/order_add.php";
            case "toOrderPage" :
                return "order/order_list.php";
            case "toPrint" :
                return "order/order_print.php";
            case "toInvoice" :
                return "order/invoice_print.php";
            case "getPro" :
                return "product/product_update.php";
            case "orderSearch":
            	return "order/order_search.php";
            case "toCheck":
            	return "order/order_check.php";
            case "toChuku":
            	return "order/order_warehousing.php";
            case "orderReturn":
            	return "order/order_return.php";
            case "toOrderReturnCancel":
            	return "order/order_cancelReturn.php";
            case "toSearchOrderList":
            	return "order/order_searchList.php";
            case "toSearchOrderListAddress":
            	return "order/order_searchListAddress.php";
            case "toYueTongji":
            	return "order/order_yuetongji.php";
            case "toDelDoubleOrder":
            	return "order/delDoubleOrder.php";
            	return "order/order_yuetongji.php";
            case "toOrderDetail":
            	return "order/order_detail.php";
            case "toOrderModify":
            	return "order/order_modify.php";
            case "toOrderReturnAdd":
            	return "order/order_return.php";
            case "toOrderReturnList":
            	return "order/order_return_list.php";
            case "getOrderReturnDetail":
            	return "order/order_return_detail.php";
            case "toOrderStatistics":
            	return "order/order_statistics.php";
            case "toOrderSearchList":
            	return "order/order_searchList.php";
            default :
                return "BaseConfig.php";
        }
    }
    }
?>

