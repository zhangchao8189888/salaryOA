<?php
/* @var $this JController */

$customerPo=$form_data['customerPo'];
$levelList=$form_data['levelList'];
$jingbanList=$form_data['jingbanList'];
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
            <a href="index.php?action=Customer&mode=getCustomerList">客户</a>
            <a href="#" class="current">修改客户  </a>
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
                        <form action="index.php?action=Customer&mode=updateCustomer" id="customer_validate" method="post" class="form-horizontal" novalidate="novalidate">
                            <div class="control-group">
                                <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>客户名称:</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $customerPo['custo_name'];?>" name="customer_name" id="customer_name" class="span11" placeholder="客户名称">
                                    <input type="hidden" value="<?php echo $customerPo['id'];?>" name="cusId" id="cusId">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><em style="color: red;padding-right: 10px;">*</em>客户编码 :</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $customerPo['custo_no'];?>" name="customer_code" id="customer_code" class="span11" placeholder="客户编码" readonly>
                                    <!--<span class="help-block">（唯一标识客户，不能重复，必填。）</span>-->
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">详细地址</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $customerPo['adress'];?>" name="customer_address" id="customer_address" class="span11" placeholder="详细地址">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">客户级别:</label>
                                <div class="controls">
                                    <select name="customer_level"  id="customer_level">
                                        <?php foreach($levelList as $val){
                                            $select = '';
                                            if ($val['id'] == $customerPo['custo_level']) {
                                                $select = 'selected';
                                            }
                                            echo "<option value='{$val["id"]}' {$select}>{$val["level_name"]}</option>";
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <!--<div class="control-group">
                                <label class="control-label">客户类别:</label>
                                <div class="controls">
                                    <select name="customer_type" id="customer_type">

                                    </select>
                                </div>
                            </div>-->
                            <div class="control-group">
                                <label class="control-label">客户经理:</label>
                                <div class="controls">
                                    <select name="customer_jingbanren" id="customer_jingbanren">
                                        <?php foreach($jingbanList as $val){
                                            $select = '';
                                            if ($val['id'] == $customerPo['op_id']) {
                                                $select = 'selected';
                                            }
                                            echo "<option value='{$val["id"]}' {$select}>{$val["jingbanren_name"]}</option>";
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">姓名</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $customerPo['custoHaed_name'];?>" name="customer_realName" id="customer_realName" class="span6">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">座机</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $customerPo['telphone_no'];?>" name="customer_phone" id="customer_phone" class="span6">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">手机</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $customerPo['moveTel_no'];?>" name="customer_mobile" id="customer_mobile" class="span6">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">QQ</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $customerPo['qq'];?>" name="customer_qq" id="customer_qq" class="span6">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">email</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $customerPo['custo_mail'];?>" name="customer_email" id="customer_email" class="span6">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">微信</label>
                                <div class="controls">
                                    <input type="text" value="<?php echo $customerPo['weixin'];?>" name="customer_weixin" id="customer_weixin" class="span6">
                                </div>
                            </div>
                            <div class="control-group control-group-line">
                                <div class="control-label">
                                    <span>生日</span>
                                </div>
                                <div class="control-input">
                                    <input type="text" id="birthday" name="birthday" value="<?php echo $customerPo['birthday_gongli'];?>"   onFocus="WdatePicker({onpicking: changeDate,isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                                </div>
                                <div class="control-label control-label-2">
                                    <span>农历</span>
                                </div>
                                <div class="control-input">
                                    <input type="text" value="<?php echo $customerPo['birthday_nongli'];?>" name="nongli" id="nongli" readonly="readonly" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">备注信息</label>
                                <div class="controls">
                                    <textarea name="customer_more" id="customer_more" class="span11"><?php echo $customerPo['remarks'];?></textarea>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success">修改</button>
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
                customer_name: { required: true },

                txbNewPwd2: { required: true, rangelength: [8, 15], equalTo: "#txbNewPwd1" }
            },
            messages: {
                customer_name:
                {
                    required: '必填'
                },
                customer_code:
                {
                    required: '（唯一标识客户，不能重复，必填。）',
                    remote : '客户编码已经存在'
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