<?php
$orderList=$form_data['orderList'];
$returnList=$form_data['returnList'];
$dateFrom=$form_data['dateFrom'];
$dateTo=$form_data['dateTo'];
$searchType=$form_data['searchType'];
$total=$form_data['total'];
$admin=$_SESSION['admin'];
?>
<style type="text/css">
    .td-text-right {
        text-align: right;
    }
    .table{
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
    }
    .clearfix:after {
        clear: both;
        content: "";
        display: block;
        height: 0;
        visibility: hidden;
    }
    .order-sum {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        border-color: #7fbbc5 #f0f0f0 #f0f0f0;
        border-image: none;
        border-right: 1px solid #f0f0f0;
        border-style: solid;
        border-width: 3px 1px 1px;
        height: 46px;
        line-height: 46px;
        overflow: hidden;
    }
    .order-sum-1,.order-sum-2 {
        border-left: 1px solid #f0f0f0;
        float: left;
        margin-left: -1px;
        padding-left: 5%;
        width: 45%;
    }
    .order-sum-money {
        font-family: Arial;
        font-weight: 900;
        margin: 0 5px;
    }
</style>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Order&mode=toOrderPage">订单</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12"><div class="widget-box">
                    <div class="widget-title">
                        <ul class="nav nav-pills">
                            <li class=""><a href="index.php?action=Order&mode=toOrderPage">订货单</a></li>
                            <li class=""><a href="index.php?action=Order&mode=toOrderReturnList">退货单</a></li>
                            <li class="active"><a href="index.php?action=Order&mode=toOrderStatistics">订单统计</a></li>
                        </ul>

                    </div>

                    <div class="widget-content tab-content ">
                        <div class="tab-pane active" id="tab1">

                            <div class="controls">
                                <form id="iForm" action="index.php?action=Order&mode=toOrderSearchList" method="post" onsubmit="checkSubmit()">
                                    筛选 ：<select id="searchType" name="searchType"   onchange="searchByType()" >
                                        <option value="all" <?php if ($searchType == 'all') echo 'selected'; ?>>全部订单</option>
                                        <option value="order" <?php if ($searchType == 'order') echo 'selected'; ?>>订单</option>
                                        <option value="return" <?php if ($searchType == 'return') echo 'selected'; ?>>退单</option>
                                    </select>
                                    时间段选择 ：<input type="text" id="dateFrom" name="dateFrom" value="<?php echo $dateFrom;?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                                    至 <input type="text" id="dateTo" name="dateTo" value="<?php echo $dateTo;?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                                    <input type="submit" class="btn btn-success" value="查询"/>
                                </form>
                            </div>
                            <div class="order-sum clearfix">
                                <div class="order-sum-1">
                                    订货单 <span class="order-sum-money">￥<span class="order-sum-money-detail"><?echo $total['order_money'];?></span></span><span><?echo $total['order_num'];?>笔</span>
                                </div>
                                <div class="order-sum-2">
                                    退货单 <span class="order-sum-money">￥ <span class="order-sum-money-detail"><?echo $total['return_money'];?></span></span><span><?echo $total['return_num'];?>笔</span>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <!--单号 	金额 	收货地址 	出库/发货 	审核状态 	操作-->
                                    <th class="tl"><div>单号</div></th>
                                    <th class="tl"><div>金额 </div></th>
                                    <th class="tl"><div>客户</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">
                                <?php
                                foreach ($orderList as $row){
                                    ?>
                                    <tr class="">
                                        <td class="tl pl10">
                                            <div><a href="index.php?action=Order&mode=getOrderById&orderId=<?php echo $row['order_no'];?>" class="serial"><?php echo $row['order_no'];?>
                                                    <?php /*if ($row['isOff']) echo '<span class="label label-success">特价</span>';*/?><!--</a>-->
                                                <input type="hidden" value="<?php echo $row['order_no'];?>" class="order-num" autocomplete="off">
                                            </div>
                                            <span class="lite-gray"><?php echo $row['add_time'];?></span>
                                            <span class="label label-success">订单</span>
                                        </td>

                                        <td class="pr10">￥<?php echo $row['realChengjiaoer'];?></td>
                                        <td class="td-text-right">
                                            <div class="orange"><?php echo $row['custer_name'];?></div>
                                        </td>
                                        <td class="tr">
                                            <a title="查看明细" class="btn btn-success btn-mini" href="index.php?action=Order&mode=getOrderById&orderId=<?php echo $row['order_no'];?>">查看明细</a>
                                        </td>
                                    </tr>
                                <?php }?>
                                <?php
                                foreach ($returnList as $returnRow){
                                    ?>
                                    <tr class="">
                                        <td class="tl pl10">
                                            <div><a href="index.php?action=Order&mode=getOrderById&orderId=<?php echo $returnRow['order_no'];?>" class="serial"><?php echo $returnRow['order_no'];?>
                                                    <?php /*if ($row['isOff']) echo '<span class="label label-success">特价</span>';*/?><!--</a>-->
                                                    <input type="hidden" value="<?php echo $returnRow['order_no'];?>" class="order-num" autocomplete="off">
                                            </div>
                                            <span class="lite-gray"><?php echo $returnRow['add_time'];?></span>
                                            <span class="label label-important">退货单</span>
                                        </td>
                                        <td class="pr10">￥<?php echo $returnRow['return_real_jin'];?></td>
                                        <td class="td-text-right">
                                            <div class="orange"><?php echo $returnRow['customer_name'];?></div>
                                        </td>
                                        <td class="tr">
                                            <a title="查看明细" class="btn btn-success btn-mini" href="index.php?action=Order&mode=getOrderReturnDetail&orderId=<?php echo $returnRow['order_no'];?>">查看明细</a>
                                        </td>
                                    </tr>
                                <?php }?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script language="javascript" type="text/javascript">
    $(function(){
        $("#pro_add").click(function(){
            $("#pro_date").val($("#shaijia_date").val());
            $('#modal-event1').modal({show:true});
        });
    });
    function searchByType () {
        $("#iForm").submit();
    }
    function checkSubmit () {
        if ($("#dateFrom").val() == '' || $("#dateTo").val() == '') {
            alert('填写完整日期');
            return false;
        }
    }
</script>


