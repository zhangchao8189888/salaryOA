<?php
$companyList=$form_data['companyList'];
$total=$form_data['total'];
$searchType=$form_data['searchType'];
$search_name = $form_data['search_name'];
$com_status = $form_data['com_status'];
$admin=$_SESSION['admin'];
?>
<script language="javascript" type="text/javascript">
    $(function(){
        //$('#myTab a:last').tab('show');//初始化显示哪个tab

        $('#myTab a').click(function (e) {
            e.preventDefault();//阻止a链接的跳转行为
            $(this).tab('show');//显示当前选中的链接及关联的content
        })
        $("#com_add").click(function(){
            /*$("#pro_date").val($("#shaijia_date").val());*/
            $.ajax(
                {
                    type: "get",
                    url: "index.php?action=Company&mode=getCode",
                    data: {type:'qiye'},
                    dataType: "json",
                    success: function(data){
                        $(".codeNo").text(data.codeNo);
                    }
                }
            );
            $("#company_validate")[0].reset();
            $('#modal-event1').modal({show:true});
        });
        $(".rowUpdate").click(function(){
            /*$("#pro_date").val($("#shaijia_date").val());*/
            var id = $(this).attr("data-id");
            $.ajax(
                {
                    type: "post",
                    url: "index.php?action=Company&mode=getCompany",
                    data: {id:id},
                    dataType: "json",
                    success: function(data){
                        //company_code,company_name,com_contact,contact_no,company_address,com_bank,bank_no,company_level,company_type,company_status
                        //add_time,update_time
                        $("#company_id").val(data.id);
                        $(".codeNo").text(data.company_code);
                        $("#company_name").val(data.company_name);
                        $("#contacts").val(data.com_contact);
                        $("#contacts_no").val(data.contact_no);
                        $("#com_address").val(data.company_address);
                        $("#com_bank").val(data.com_bank);
                        $("#bank_no").val(data.bank_no);
                        $("#company_level").val(data.company_level);
                        $("#company_type").val(data.company_type);
                        $("#company_status").val(data.company_status);
                    }
                }
            );
            $('#modal-event1').modal({show:true});
        });
    });
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">企业管理</a>
            <a href="#">企业信息</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12"><div class="widget-box">
                    <!--<div class="widget-title">
                        <ul class="nav nav-pills">
                            <li class="active"><a href="index.php?action=Order&mode=toOrderPage">订货单</a></li>
                            <li class=""><a href="index.php?action=Order&mode=toOrderReturnList">退货单</a></li>
                            <li class=""><a href="index.php?action=Order&mode=toOrderStatistics">订单商品统计</a></li>
                        </ul>

                    </div>-->

                    <div class="widget-content tab-content ">
                        <div class="tab-pane active" id="tab1">

                            <div class="controls">
                                <form id="iForm" action="index.php?action=Company&mode=toCompanyList" method="post">
                                    <select id="searchType" name="searchType"   onchange="searchByType()" >
                                        <option value="name" <?php if ($searchType == 'name') echo 'selected'; ?>>企业名称</option>
                                        <option value="status" <?php if ($searchType == 'status') echo 'selected'; ?>>企业状态</option>
                                    </select>
                                    <input type="text" value="<?php echo $search_name;?>" name="search_name" id="search_name" placeholder="请输入企业名称"/>
                                    <select id="com_status" name="com_status"   onchange="searchByStatus()" style="display: none">
                                        <option value="1" <?php if ($com_status == '1') echo 'selected'; ?>>启用</option>
                                        <option value="0" <?php if ($com_status == '0') echo 'selected'; ?>>停用</option>
                                    </select>
                                    <input type="submit" value="查询"/>
                                    <input type="hidden" value="" id="pro_id"/>
                                    <input type="hidden" value="" id="pro_code"/>
                                    <div style="float: right;margin-right: 20px"><a href="#" id="com_add" class="btn btn-success" >新增企业</a></div>
                                </form>
                            </div>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <!-- 编号 企业名称 联系人 联系方式 地址 银行账户 客户等级 -->
                                    <th class="tl"><div></div></th>
                                    <th class="tl"><div>编号</div></th>
                                    <th class="tl"><div>企业名称</div></th>
                                    <!--<th class="tl"><div>金额</div></th>-->
                                    <th class="tl"><div>联系人</div></th>
                                    <th class="tl"><div>联系方式</div></th>
                                    <th class="tl"><div>地址</div></th>
                                    <th class="tl"><div>银行账户</div></th>
                                    <th class="tl"><div>企业类型</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">

                                <?php
                                foreach ($companyList as $row){
                                    ?>
                                    <tr class="">
                                        <td>    </td>

                                        <td class="tl pl10">
                                             <div><?php echo $row['company_code'];?></div>
                                        </td>
                                        <td class="tl pl10">
                                             <div><?php echo $row['company_name'];?></div>
                                        </td>
                                        <td class="tl pl10">
                                             <div><?php echo $row['com_contact'];?></div>
                                        </td>
                                        <td class="tl pl10">
                                             <div><?php echo $row['contact_no'];?></div>
                                        </td>
                                        <td class="tl pl10">
                                             <div><?php echo $row['company_address'];?></div>
                                        </td>
                                        <td class="tl pl10">
                                             <div><?php echo $row['bank_no'];?></div>
                                        </td>
                                        <td class="tl pl10">
                                             <div><?php echo $row['company_type'];?></div>
                                        </td>
                                        <td class="tr">
                                            <a title="修改" data-id="<?php echo $row['id'];?>"  class="rowUpdate pointer theme-color">修改</a>
                                            <div class="cb"></div>
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
            <div class="span12" style="margin-left:0;">
                <div class="widget-box">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#home">劳动派遣合同</a></li>
                        <li><a href="#profile">企业账户</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="home">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <!-- 合同编号 合同名称 甲方 甲方法人 乙方 乙方法人 合同开始日期 合同截止日期 合同年限 支付方式 合同状态-->
                                    <th class="tl"><div>合同编号</div></th>
                                    <th class="tl"><div>合同名称</div></th>
                                    <th class="tl"><div>甲方</div></th>
                                    <th class="tl"><div>甲方法人</div></th>
                                    <th class="tl"><div>乙方</div></th>
                                    <th class="tl"><div>乙方法人</div></th>
                                    <th class="tl"><div>合同开始日期</div></th>
                                    <th class="tl"><div>合同截止日期</div></th>
                                    <th class="tl"><div>合同年限</div></th>
                                    <th class="tl"><div>支付方式</div></th>
                                    <th class="tl"><div>合同状态</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">
<!--                                PQ-1012040001 劳动派遣合同 北京市海淀区公安局 xxx 中企基业 吴总 20101204 20111204 1年 电汇 已完成-->
                                    <tr class="">
                                        <td>PQ-1012040001</td>
                                        <td>劳动派遣合同</td>
                                        <td>北京市海淀区公安局</td>
                                        <td>xxx</td>
                                        <td>中企基业</td>
                                        <td>吴总</td>
                                        <td>20101204</td>
                                        <td>20111204</td>
                                        <td>1年</td>
                                        <td>电汇</td>
                                        <td><em style="color: #008000">已完成</em></td>

                                    </tr>
                                    <tr class="">
                                        <td>PQ-1012040004</td>
                                        <td>劳动派遣合同</td>
                                        <td>北京市海淀区公安局</td>
                                        <td>xxx</td>
                                        <td>中企基业</td>
                                        <td>吴总</td>
                                        <td>20111204</td>
                                        <td>20151204</td>
                                        <td>4年</td>
                                        <td>电汇</td>
                                        <td><em style="color: red">未完成</em></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="profile">

                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <!-- 资金来往方 资金类型 收入 支出 支付方式 填报人 填报时间 备注
                                    清华大学 工资汇入 520210.15 支票支付 王子诚 2014-12-04 14:20:30 2015年1月份工资汇入
                                    -->
                                    <th class="tl"><div>资金来往方</div></th>
                                    <th class="tl"><div>资金类型</div></th>
                                    <th class="tl"><div>收入</div></th>
                                    <th class="tl"><div>支出</div></th>
                                    <th class="tl"><div>支付方式</div></th>
                                    <th class="tl"><div>填报人</div></th>
                                    <th class="tl"><div>填报时间</div></th>
                                    <th class="tl"><div>备注</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">
                                <tr class="">
                                    <td>清华大学</td>
                                    <td>工资汇入</td>
                                    <td>￥<em style="color: red">52000.15</em></td>
                                    <td>￥<em style="color: #008000">0.00</em></td>
                                    <td>支票支付</td>
                                    <td>王子诚</td>
                                    <td>2014-12-04 14:20:30</td>
                                    <td class="tl pl10">
                                        2015年1月份工资汇入
                                    </td>
                                </tr>
                                <tr class="">
                                    <td>海淀公安局</td>
                                    <td>垫付冲回</td>
                                    <td>￥<em style="color: red">0.00</em></td>
                                    <td>￥<em style="color: #008000">-600</em></td>
                                    <td>银行转账</td>
                                    <td>王子诚</td>
                                    <td>2014-12-04 14:20:30</td>
                                    <td class="tl pl10">
                                        1月张超公积金垫付冲回
                                    </td>
                                </tr>
                                <tr class="">
                                    <td>合计</td>
                                    <td></td>
                                    <td>￥<em style="color: red">52000.15</em></td>
                                    <td>￥<em style="color: #008000">-600</em></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="tl pl10">

                                    </td>
                                </tr>
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

    });
    function searchByType () {
        var type = $("#searchType").val();
        if (type == 'name') {
            $("#search_name").show();
            $("#com_status").hide();
        } else if (type == 'status') {
            $("#search_name").hide();
            $("#com_status").show();
        }
    }
    function searchByStatus() {

    }
</script>
<script language="javascript" type="text/javascript" src="common/common-js/company.js" charset="utf-8"></script>
<div class="modal hide" id="modal-event1">

<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>企业信息新增</h3>
    </div>
    <form action="" id="company_validate" method="post" class="form-horizontal"  novalidate="novalidate">
    <div class="modal-body">
        <div class="designer_win">
             <div class="tips">客户编号：<span class="codeNo"></span><input type="hidden" value="" id="company_id" name="company_id"/></div>
            <div class="tips"><em style="color: red;padding-right: 10px;">*</em>企业名称：<input type="text" maxlength="20" id="company_name"name="company_name"  /></div>
            <div class="tips"><em style="color: red;padding-right: 10px;">*</em>联系人：<input type="text" maxlength="20" id="contacts"name="contacts"  /></div>
            <div class="tips"><em style="color: red;padding-right: 10px;">*</em>联系方式：<input type="text" maxlength="20" id="contacts_no"name="contacts_no"  /></div>
            <div class="tips">公司地址：<input type="text" maxlength="20" id="com_address"  /></div>
            <div class="tips">开户行：<input type="text" maxlength="20" id="com_bank"  /></div>
            <div class="tips">银行帐号：<input type="text" maxlength="20" id="bank_no"  /></div>
            <div class="tips">企业类型：<select name="company_type" id="company_type"/>
                    <option value="1">国有企业</option>
                    <option value="2">事业单位</option>
                    <option value="3">名营企业</option>
                    <option value="4">外资企业</option>
                    <option value="5">政府部门</option>
                </select></div>
            <div class="tips">客户级别：<select name="company_level" id="company_level">
                    <option value="1">普通客户</option>
                    <option value="2">中级客户</option>
                    <option value="3">高级客户</option>
                </select></div>
            <div class="tips">状态：<select name="company_status" id="company_status">
                    <option value="1">启用</option>
                    <option value="0">停用</option>
                </select></div>
        </div>
    </div>

    <div class="modal-footer modal_operate">
        <button type="submit" class="btn btn-primary">添加</button>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
    </form>
</div>

