<?php
$productPo=$form_data['productPo'];
?>
<style>
    .control-group .control-input {
        float: left;
        line-height: 32px;
        padding-left: 10px;
    }
    .control-group-line .control-label-2 {
        width: 50px;
    }
</style>
<script type="text/javascript">
    $(function(){
        $('#test').bind('input propertychange', function() {
            alert("aa");
            $('#content').html($(this).val().length + ' characters');
        });
    });
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Product&mode=getProductList">商品</a>
            <a href="#" class="current">新增商品</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span6">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>基础资料</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form action="index.php?action=Product&mode=addProduct" id="customer_validate" method="post" class="form-horizontal" novalidate="novalidate">
                            <div class="control-group">
                                <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>商品型号:</label>
                                <div class="controls">
                                    <input type="text" name="product_code" id="product_code" class="span11" placeholder="商品型号" value="<?php echo $productPo['pro_code']?>" readonly/>
                                    <input type="hidden" id="proId" name="proId"   value="<?php echo $productPo['id']?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">供应商:</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $productPo['pro_supplier']?>" name="product_supplier" id="product_supplier" class="span11">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>货品简称:</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $productPo['pro_name']?>" name="product_name" id="product_name" class="span11" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">货品类别:</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $productPo['pro_type']?>" name="product_type" id="product_type" class="span11" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">单位:</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $productPo['pro_unit']?>" name="product_unit" id="product_unit" class="span5" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">数量:</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $productPo['pro_num']?>" name="product_num" id="product_num" class="span3" value="0" readonly>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">有无折扣:</label>
                                <div class="controls">
                                    <select name="flag" id="flag">
                                        <option value="0" <?php if($productPo['pro_flag']==0)echo 'selected'?>>无折扣</option>
                                        <option value="1" <?php if($productPo['pro_flag']==1)echo 'selected'?> >有折扣</option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">市场价格:</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $productPo['pro_price']?>" name="product_price" id="product_price" class="span5" >
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success">保存</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="common/js/datepicker/WdatePicker.js"></script>
<script language="JavaScript" type="text/javascript">
    $(document).ready(function() {
        $("#customer_validate").validate({
            rules: {
                product_code :  {
                    required: true
                },
                product_num: {
                    required: true,
                    number:true
                },
                product_price : {
                    required: true,
                    number:true
                }

            },
            messages: {
                product_num:
                {
                    required: '必填'
                },
                product_code:
                {
                    required: '（唯一标识客户，不能重复，必填。）'
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight:function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('success');
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }


        });

    });



    jQuery.extend(jQuery.validator.messages, {
        required: "必选字段",
        remote: "请修正该字段",
        email: "请输入正确格式的电子邮件",
        url: "请输入合法的网址",
        date: "请输入合法的日期",
        dateISO: "请输入合法的日期 (ISO).",
        number: "请输入合法的数字",
        digits: "只能输入整数",
        creditcard: "请输入合法的信用卡号",
        equalTo: "请再次输入相同的值",
        accept: "请输入拥有合法后缀名的字符串",
        maxlength: jQuery.validator.format("请输入一个 长度最多是 {0} 的字符串"),
        minlength: jQuery.validator.format("请输入一个 长度最少是 {0} 的字符串"),
        rangelength: jQuery.validator.format("请输入 一个长度介于 {0} 和 {1} 之间的字符串"),
        range: jQuery.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
        max: jQuery.validator.format("请输入一个最大为{0} 的值"),
        min: jQuery.validator.format("请输入一个最小为{0} 的值")
    });
    function pack(dp){
        if(!confirm('日期框原来的值为: '+dp.cal.getDateStr()+', 要用新选择的值:' + dp.cal.getNewDateStr() + '覆盖吗?'))
            return true;
    }
    function changeDate(dp) {

        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Customer&mode=sumNongli",
                async:false,
                data: {
                    date: dp.cal.getNewDateStr()
                },
                dataType: "json",
                success: function(data){
                    if (data.data){
                        $("#nongli").val(data.data);
                    }

                }
            }
        );
    }
</script>