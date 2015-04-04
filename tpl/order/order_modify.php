<?php
$customer=$form_data['customer'];
$orderTotal=$form_data['orderTotal'];
$orderList=$form_data['orderList'];
$logList=$form_data['logList'];
$admin=$_SESSION['admin'];
?>
<script language="javascript" type="text/javascript">
    $(function(){
        $("#pro_add").click(function(){
            $("#pro_date").val($("#shaijia_date").val());
            $('#modal-event1').modal({show:true});
        });
    });
</script>
<style type="text/css">
    .search_suggest{margin-left: 185px;}
    /* mailBox */
    .gover_search_key{
        border: 1px solid #0591aa;
        font-family: "Microsoft Yahei",Tahoma,Arial;
        height: 40px;
        line-height: 50px;
        outline: 0 none;
        width: 100%;

    }

    .search_suggest{background:#fff;border:1px solid #ddd;padding:3px 5px 0px;position:absolute;z-index:9999;display:none;-webkit-box-shadow:0px 2px 7px rgba(0, 0, 0, 0.35);-moz-box-shadow:0px 2px 7px rgba(0, 0, 0, 0.35);}
    .search_suggest p{width:100%;margin:0;padding:0;height:20px;line-height:20px;clear:both;font-size:12px;color:#ccc;cursor:default;}
    .search_suggest ul{padding:0;margin:0;}
    .search_suggest li{font-size:12px;height:22px;line-height:22px;color:#939393;font-family:'Tahoma';list-style:none;cursor:pointer;overflow:hidden;}
    .search_suggest .cmail{color:#000;background:#e8f4fc;}
    .search_ul{
        position: relative;
        overflow: auto;
        height: 150px;
    }
    .ui-icon-ellipsis {
        border: medium none;
        right: 0;
        top: 65%;
        width: 16px;
        height: 16px;
        margin-top: -8px;
        cursor: pointer;
        overflow: hidden;
        position: absolute;
        /*#background: url(../../images/icon.png) 0 -70px no-repeat;*/
    }
    /*li{
        padding-left:10px;
    }*/
    .ui-combo-wrap {
        position: relative;
        display: inline-block;
        height: 30px;
        vertical-align: middle;
        background-color: #fff;
        border: 1px solid #d6dee3;
        color: #555;
        overflow: hidden;
        zoom: 1;
    }
    .extra-list-ctn {
        border-top: 1px solid #d6dee3;
        padding-left: 10px;
        line-height: 26px;
        background-color: #f5f5f5;
    }
    .quick-add-link {
        line-height: 26px;
        position: relative;
        cursor: pointer;
        outline: medium none;
        text-decoration: none;
        color: #0591aa;
    }
    .order-total {
        min-height: 40px;
        border: 1px solid #e5e8ea;
        margin-top: -1px;
        overflow: hidden;
    }
    .order-total .total-r {
        width: 260px;
        float: right;
        padding: 10px 20px;
    }
    .total-r .total-group {
        line-height: 28px;
    }
    .total-group label {
        margin-top: 3px;
        width: 135px;
        text-align: right;
        float: left;
        font-size : 11px;
    }
    .order-total .total-l {
        margin-right: 303px!important;
        margin-right: 300px;
        padding: 0px;
    }
    .control-group {
        margin-top: 10px;
        overflow: hidden;
        min-height: 32px;
        line-height: 32px;
    }
    .ui-chk {
        padding-left: 20px;
        height: 20px;
        display: inline-block;
        cursor: pointer;
        line-height: 6px;
        vertical-align: middle;
    }
    .fl {
        float: left!important;
    }
    .w80 {
        width: 80px!important;
    }
    .tr {
        text-align: right!important;
    }
    .ui-input-line-dis {
        background: #fcfcfc;
        cursor: not-allowed;
    }
    .ui-input-line {
        outline: 0;
        border: 0;
        height: 14px;
        line-height: 18px;
        border-bottom: 1px solid #d6dee3;
        color: #555;
        padding: 2px 12px;
        font-size: 12px;
    }
    .total-r .total-group .total {
        width: 110px;
        text-align: right;
        margin-left: 130px;
    }
    .red, a.red:hover {
        color: red;
    }
    .order .remark .inp-remark {
        font-size: 12px;
        padding-right: 20px;
        width: 100%;
    }
    .ui-textarea-line {
        color: rgb(255,0,0);
        border-left: medium none;
        border-right: medium none;
        border-top: medium none;
        border-bottom: 1px solid rgb(192,192,192);
        color: #555;
        font-family: verdana,"宋体","Microsoft Yahei",Tahoma,Arial;
        font-size: 12px;
        height: 32px;
        line-height: 32px;
        outline: 0 none;
        padding: 0 5px;
        resize: none;
    }
    .remark {
        margin-top: 30px;
    }
    .control-group .control-label {
        float: left;
        height: 32px;
        line-height: 32px;
        text-align: right;
        width: 110px;
        font-size: 12px;
    }
    .remark .control-input {
        float: none;
        margin-left: 110px;
    }
    .remark .inp-remark {
        width: 100%;
        padding-right: 20px;
        font-size: 12px;
    }
    .search {
        float: left;
    }
    .modify-a {
        float: right;
        margin: 4px 50px 0;
        font-size: 14px;
        width: 100px;
    }
    .theme-color, a.theme-color:hover {
        color: #1596ad;
    }
</style>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Order&mode=toOrderPage">订单</a>
            <a href="#" class="current">订单修改</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">

                    <div class="widget-content tab-content">
                        <div class="search-form">

                            <div class="row-fluid1">
                                <div class="search">
                                    <span style="font-size: 16px;"><?php echo $orderTotal['custer_name'];?>【<?php echo $customer['custo_level_name'];?>】</span>
                                    单号：<span style="font-size: 14px;"><?php echo $orderTotal['order_no'];?></span>
                                    <input type="hidden" id="cId" name="cId" value="<?php echo $orderTotal['custer_no'];?>">
                                    <input type="hidden" id="orderNo" name="orderNo" value="<?php echo $orderTotal['order_no'];?>">
                                    <input type="hidden" id="dingDate" name="dingDate" value="<?php echo $orderTotal['ding_date'];?>">
                                    <input type="hidden" id="custo_discount" name="custo_discount" value="<?php echo $orderTotal['zhekou'];?>">
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="widget-content tab-content">

                        <div class="tab-pane active" id="tab1">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <!--商品 数量 单位 单价 小计 备注-->
                                    <th class="tl" style="width: 25px;"><div></div></th>
                                    <th class="tl" style="width: 50px;"><div></div></th>
                                    <th class="tl" style="width: 605px;"><div>商品</div></th>
                                    <th class="tl" style="width: 110px;"><div>数量</div></th>
                                    <th class="tl" style="width: 69px;"><div>单位</div></th>
                                    <th class="tl" style="width: 138px;"><div>单价</div></th>
                                    <th class="tl" style="width: 206px;"><div>小计</div></th>
                                    <th class="tl" style="width: 80px;"><div>备注</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodys">
                                <?php $rowNum = 1;foreach ($orderList['data'] as $key => $val) {
                                    ?>
                                    <tr id="<?php echo $rowNum;?>">
                                        <td style="text-align: center;"><?php echo $rowNum;?></td>
                                        <td style="text-align: center;"><a style="cursor: pointer;" class="icon-plus" title="新增行"></a>
                                            <a class="icon-minus" style="cursor: pointer;" title="删除行"></a></td>
                                        <td class="product_add" style="width: 300px;height:20px;"><?php echo $val['inputText'];?></td>
                                        <td class="product_num" style="text-align:center;"><?php echo $val['pro_num'];?></td>
                                        <td style="text-align:center;"><?php echo $val['pro_unit'];?></td>
                                        <td style="text-align:right;"><?php echo $val['price'];?></td>
                                        <td style="text-align:right;"><?php echo $val['order_jiner'];?></td>
                                        <td></td>
                                    </tr>
                                    <?php $rowNum++; }?>
                                </tbody>
                            </table>
                            <div class="product-promotion">
                                <ul>
                                    <li style="display:none;" class="template">【赠品】<input type="hidden" class="productId" autocomplete="off"><span class="code"></span> <span class="name"></span><span class="spec"></span>&#12288;<span class="count"></span><span class="unit"></span></li>
                                </ul>
                            </div>
                            <div class="order-total">
                                <div class="total-r">

                                    <div class="total-group">
                                        <label class="total-label total-money-label">合计：</label>
                                        <div class="total">￥<span class="total-money"><?php echo $orderTotal['chengjiaoer'];?></span></div>
                                    </div>

                                    <div class="total-group" style="display:none;">
                                        <label class="total-label total-order-priviledges-label">订单促销优惠：</label>
                                        <div class="total red">-￥<span class="total-order-priviledges-money">0.00</span></div>
                                    </div>

                                    <div class="total-group" style="">
                                        <label class="total-label total-rel-money-label" >应付总额：</label>
                                        <div class="total red">￥<span class="total-rel-money"><?php echo $orderTotal['realChengjiaoer'];?></span></div>
                                    </div>
                                </div>
                                <div class="total-l chks">
                                    <?php if($orderTotal['isOff']) {?>
                                    <div class="control-group">
                                        <label class="ui-chk chk fl"><div class="controls">
                                                <input type="checkbox" id="discounts" name="isUseBackPoint" checked>
                                            </div></label><span class="is-discount-order-con fl">已申请特价，请输入获批订单金额：￥（<input type="text" id="inp-discount-order" style="border: 0;height: 15px;margin-bottom: 8px;" class="ui-input-line w80 tr" value="<?php echo $orderTotal['realChengjiaoer'];?>" autocomplete="off">）</span><a href="javascript:void(0)" class="tip-orange ui-icon-info discount-tip" data-hasqtip="3"></a>
                                    </div>
                                    <?php }?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions">
                        <input id="orderModify" class="btn btn-primary" type="submit" value="提交">
                        <input id="cancel" class="btn" type="button" value="取消">

                        <div id="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="search_suggest" id="gov_search_suggest">
    <ul class="search_ul">

    </ul>
    <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择商品</a></div>
</div>
<script language="javascript" type="text/javascript" src="common/order/order.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/order/orderAdd.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript">
    $(function(){
        Order.tableList = <?php echo json_encode($orderList['data'])?>;
        Customer.oCustomer.info = <?php echo json_encode($customer)?>;
        Order.Table.row = <?php echo $rowNum;?>

    });
</script>


