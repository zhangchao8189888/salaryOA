<?php
$shoukuanList=$form_data['shoukuanList'];
$total=$form_data['total'];
$searchType=$form_data['searchType'];
$search_name = $form_data['search_name'];
$com_status = $form_data['com_status'];
$errorMsg=$form_data['error'];
$succ=$form_data['succ'];
$admin=$_SESSION['admin'];
?>
<script src="common/hot-js/handsontable.full.js"></script>
<script src="common/common-js/fukuandan.js"></script>
<link rel="stylesheet" media="screen" href="common/hot-js/handsontable.full.css">
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $('#myTab a').click(function (e) {
            e.preventDefault();//阻止a链接的跳转行为
            $(this).tab('show');//显示当前选中的链接及关联的content
        })
        $("#com_add").click(function(){
            $('#modal-event1').modal({show:true});
        });
    });
    function chanpinDownLoad(){
        $("#iForm").attr("action","index.php?action=Salary&mode=fukuandanDownload");
        //$("#nfname").val($("#newfname").val());
        $("#iForm").submit();
    }
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">工资管理</a>
            <a href="#">收款管理</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12"><div class="widget-box">
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
                                    <font color="red"><?php if($errorMsg)echo $errorMsg?></font>
                                    <font color="green"><?php if($succ)echo $succ?></font>
                                    <!--<form id="iformD"action="index.php?action=Company&mode=toCompanyList" method="post">-->
                                        <input type="button" value="下载工资导入模版" onclick="chanpinDownLoad()" class="btn btn-primary"/>
                                    <!--</form>-->
                                    <div style="float: right;margin-right: 20px"><a href="#" id="com_add" class="btn btn-success" >新增付款单</a></div>
                                </form>
                            </div>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <!-- 编号 企业名称 联系人 联系方式 地址 银行账户 客户等级 -->
                               <!-- <th class="tl"><div>收款单号</div></div></th>-->
                        <th class="tl"><div>企业名称</div></th>
                        <th class="tl"><div>工资月份</div></th>
                        <th class="tl"><div>工资总额</div></th>
                        <th class="tl"><div>制单人</div></th>
                        <th class="tl"><div>制单时间</div></th>
                        <th class="tl"><div>是否入账</div></th>
                        <th class="tl"><div>备注</div></th>
                        <th class="tl"><div>操作</div></th>
                        </tr>
                        </thead>
                        <tbody  class="tbodays">
                        <?php foreach($shoukuanList as $row) {
                            ?>
                            <tr class="">

                                <td class="tl pl10">
                                    <div><?php echo $row['company_name'];?></div>
                                </td>
                                <td class="tl pl10">
                                    <div><?php echo $row['salaryTime'];?></div>
                                </td>
                                <td class="tl pl10">
                                    <div><?php echo $row['salSumValue'];?></div>
                                </td>
                                <td class="tl pl10">
                                    <div><?php echo $row['admin_name'];?></div>
                                </td>
                                <td class="tl pl10">
                                    <div><?php echo $row['create_time'];?></div>
                                </td>
                                <td class="tl pl10">
                                    <div><?php if($row['fukuan_status'])
                                            echo "<em style='color: green'>已入账</em>";
                                            else echo "<em style='color: red'>未入帐</em>";
                                        ?></div>
                                </td>

                                <td class="tl pl10">
                                    <div><?php echo $row['memo'];?></div>
                                </td>
                                <td class="tr">
                                    <?php if ($row['fukuan_status']<1){?>
                                            <a title="修改" data-id="<?php echo $row['id'];?>" style="cursor:pointer"  class="rowUpdate theme-color">修改</a>
                                        <?php }?>
                                    <a title="查看" data-file="<?php echo $row['file_path'];?>" data-id="<?php echo $row['salTime_id'];?>"data-status="<?php echo $row['fukuan_status'];?>" fukuan-id="<?php echo $row['id'];?>" style="cursor:pointer"  class="rowCheck theme-color">查看付款详细</a>

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
                    <li class="active"><a href="#home">工资查询</a></li>
                    <li><a href="#profile">企业垫付<em style="color: red" id="dianfuNum"></em></a></li>
                    <li><a href="#yanfu">企业延付<em style="color: red" id="yanfuNum"></em></a></li>
                    <li><a href="#error">不一致<em style="color: red" id="errorNum"></em></a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="home">
                        <div class="controls">
                            <!-- checked="checked"-->
                            <input type="checkbox" id="colHeaders" autocomplete="off"> <span>锁定前两列</span>
                            <input type="checkbox" id="rowHeaders" autocomplete="off"> <span>锁定第一行</span>
                            <input type="button" value="入账" class="btn btn-success" id="comeIn" style="display: none"/>
                            <input type="button" value="保存垫付延付" class="btn btn-success" id="saveYanfu" style="display: none"/>
                            <input type="hidden" id="salaryTimeId" name="salaryTimeId" value=""/>
                            <input type="hidden" id="fukuandanId" name="fukuandanId" value=""/>
                        </div>
                        <div id="exampleGrid" class="dataTable" style="width: 1000px; height: 400px; overflow: auto"></div>
                    </div>
                    <div class="tab-pane" id="profile">
                        <div id="dianfuGrid" class="dataTable" style="width: 1000px; height: 400px; overflow: auto"></div>
                    </div>
                    <div class="tab-pane" id="yanfu">
                        <div id="yanfuGrid" class="dataTable" style="width: 1000px; height: 400px; overflow: auto"></div>
                    </div>
                    <div class="tab-pane" id="error">
                        <div id="errorGrid" class="dataTable" style="width: 1000px; height: 400px; overflow: auto"></div>
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
        <h3>付款单新增</h3>
    </div>
    <form action="index.php?action=Salary&mode=saveFukuandan" enctype="multipart/form-data" id="company_validate" method="post" class="form-horizontal"  novalidate="novalidate">
        <div class="modal-body">
            <div class="designer_win">
                <input type="hidden" value="" id="fid" name="fid"/>
<!--                <div class="tips">付款通知单号：<span class="codeNo"></span><input type="hidden" value="" id="shouNo" name="shouNo"/></div>-->
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>企业名称：<input type="text" maxlength="20" id="e_company"name="e_company" autocomplete="off" /><input type="hidden" value="" id="company_id" name="company_id"/></div>
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>工资月份：
                    <select id="salTimeId" name="salTimeId"   onchange="getSalarySumInfo()" >

                    </select>
                </div>
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>导入付款单：<input type="hidden" name="max_file_size" value="10000000"/><input name="file" type="file"/></div>
                <div class="tips" id="fileDiv"><em style="color: red;padding-right: 10px;">*</em>导入付款单：<em id="fileName"></em><input type="hidden" name="fileNameValue"id="fileNameValue"/></div>
                <div class="tips">工资总额：<label id="salSum"name="salSum"></label><input type="hidden" name="salSumValue"id="salSumValue"/></div>
                <div class="tips">制单人：<input type="text" maxlength="20" id="op_name" name="op_name" value="<?php echo $admin['name'];?>" />
                    <input type="hidden" name="op_id" value="<?php echo $admin['id'];?>"/>
                </div>
                <div class="tips">是否入账：<span style="color: red">未入账</span></div>
                <div class="tips">备注：<textarea id="more" name="more">

                    </textarea></div>
            </div>
        </div>

        <div class="modal-footer modal_operate">
            <button type="submit" class="btn btn-primary">暂存</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </form>
    <div class="search_suggest" id="custor_search_suggest">
        <ul class="search_ul">

        </ul>
        <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
    </div>
</div>

