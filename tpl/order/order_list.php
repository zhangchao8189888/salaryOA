<?php
$orderList=$form_data['orderList'];
$total=$form_data['total'];
$searchType=$form_data['searchType'];
$by=$form_data['by'];
$up=$form_data['up'];
$admin=$_SESSION['admin'];
?>
<style type="text/css">
    .ui-slide-gray-a {
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        color: #555;
        background-color: #808080;
        display: inline-block;
    }
    .step {
        height: 40px;
    }

    .ui-slide-gray-a.current {
        color: #FFF;
        background-position: -108px -79px;
    }
    .step .step-item em {
        margin-right: 10px;
        font-style: normal;
    }

    .step.step1 .step-item-1 {
        background-color: #e0f0f5;
        background-position: right -40px;
    }

    .step.step1 .step-item-2 {
        background-color: #f3f5f6;
        background-position: right 0;
    }
    .step.step1 .step-item-3 {
        background: #f3f5f6;
    }
    .ui-slide-gray-a.current {
        color: #FFF;
        background-position: -108px -79px;
    }

    .step .step-item {
        float: left;
        width: 33.3%;
        text-align: center;
        font-size: 16px;
        color: #666;
        line-height: 40px;
        height: 40px;
        background: #f3f5f6 url(common/img/step.png) no-repeat;
    }
    .step .step-item em {
        margin-right: 10px;
        font-style: normal;
    }
    .ui-slide-gray-a.current {
        color: #FFF;
        background-position: -108px -79px;
    }
    .ui-slide-gray-a {
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        color: #555;
        background: url(common/img/step.png) -148px -79px no-repeat;
        display: inline-block;
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
                            <li class="active"><a href="index.php?action=Order&mode=toOrderPage">订货单</a></li>
                            <li class=""><a href="index.php?action=Order&mode=toOrderReturnList">退货单</a></li>
                            <li class=""><a href="index.php?action=Order&mode=toOrderStatistics">订单商品统计</a></li>
                        </ul>

                    </div>

                    <div class="widget-content tab-content ">
                        <div class="tab-pane active" id="tab1">

                            <div class="controls">
                                <form id="iForm" action="index.php?action=Order&mode=toOrderPage" method="post">
                                筛选 ：<select id="searchType" name="searchType"   onchange="searchByType()" >
                                      <option value="all" <?php if ($searchType == 'all') echo 'selected'; ?>>全部订单</option>
                                      <option value="yifu" <?php if ($searchType == 'yifu') echo 'selected'; ?>>已付</option>
                                      <option value="weifu" <?php if ($searchType == 'weifu') echo 'selected'; ?>>未付</option>
                                      <option value="xianjin" <?php if ($searchType == 'xianjin') echo 'selected'; ?>>现金</option>
                                      <option value="shuaka" <?php if ($searchType == 'shuaka') echo 'selected'; ?>>刷卡</option>
                                      <option value="dianhui" <?php if ($searchType == 'dianhui') echo 'selected'; ?>>电汇</option>
                                        </select>
                                排序 ：<select id="by" name ='by'  onchange="searchByType()" >
                                    <option value="order_no" <?php if ($by == 'order_no') echo 'selected'; ?>>订单号</option>
                                    <option value="ding_date" <?php if ($by == 'ding_date') echo 'selected'; ?>>订货时间</option>
                                </select>
                                升降 ：<select id="up" name ='up'   onchange="searchByType()" >
                                    <option value="asc" <?php if ($up == 'asc') echo 'selected'; ?>>升序</option>
                                    <option value="desc" <?php if ($up == 'desc') echo 'selected'; ?>>降序</option>
                                </select>
                                <input type="hidden" value="" id="pro_id"/>
                                <input type="hidden" value="" id="pro_code"/>
                                <div style="float: right;margin-right: 20px"><a href="index.php?action=Order&mode=toAdd" class="btn btn-success" >新增订单</a></div>
                                </form>
                            </div>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <!--单号/下单时间 	代理商名称 	金额 	出库/发货 	状态 	操作-->
                                    <th class="tl"><div></div></th>
                                    <th class="tl"><div>单号/下单时间</div></th>
                                    <th class="tl"><div>客户名称</div></th>
                                    <!--<th class="tl"><div>金额</div></th>-->
                                    <th class="tl"><div>实际金额</div></th>
                                    <th class="tl"><div>付款方式</div></th>
                                    <th class="tl"><div>付款状态</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">

                                <?php
                                foreach ($orderList as $row){
                                    ?>
                                    <tr class="">
                                        <td>    </td>
                                        <td class="tl pl10">
                                            <div><a href="index.php?action=Order&mode=getOrderById&orderId=<?php echo $row['order_no'];?>" class="serial"><?php echo $row['order_no'];?>
                                                    <?php if ($row['isOff']) echo '<span class="label label-success">特价</span>';?></a>
                                                <input type="hidden" value="<?php echo $row['order_no'];?>" class="order-num" autocomplete="off">
                                            </div>
                                            <span class="lite-gray"><?php echo $row['ding_date'];?></span>
                                        </td>
                                        <td class="tl pl10"><a href="/customer/customer?action=load&amp;id=25762928" target="_blank" class="company"><?php echo $row['custer_name'];?></a></td>
                                        <!--<td class="tr pr10">￥<?php /*echo $row['chengjiaoer'];*/?></td>-->
                                        <td class="tr pr10">￥<?php echo $row['realChengjiaoer'];?></td>
                                        <td class="tc order-logistics-status">
                                            <span><?php echo $row['pay_type'];?></span><br>
                                        </td>
                                        <td class="tc">
                                            <div class="orange"><?php echo $row['pay_status'];?></div>
                                        </td>
                                        <td class="tr">
                                            <a title="订单查详情" class="theme-color" href="index.php?action=Order&mode=getOrderById&orderId=<?php echo $row['order_no'];?>">订单详情</a>



                                            <div class="cb"></div>

                                            <!--<a title="添加收款记录" class="theme-color order-pay" href="javascript:void(0)">添加收款记录</a>-->

                                        </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                            <?php require_once("tpl/page.php"); ?>
                            <div class="total_page">共 <span class="redtitle"><?php echo $total ;?></span> 条记录</div>
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
</script>


