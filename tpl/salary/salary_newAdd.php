<?php
$salTime=$form_data['salTime'];
?>
<style type="text/css">

</style>
<script language="javascript"  type="text/javascript">
    $(function(){
        $('#myTab a').click(function (e) {
            e.preventDefault();//阻止a链接的跳转行为
            $(this).tab('show');//显示当前选中的链接及关联的content
        })

    });
    function chanpinDownLoad(){
        $("#iform").attr("action","index.php?action=Admin&mode=fileProDownload");
        //$("#nfname").val($("#newfname").val());
        $("#iform").submit();
    }
    function b(){
        $("#iform").attr("action","index.php?action=Salary&mode=sumSalary");
        $("#iform").submit();
    }
    function nian_b(){
        $("#iform_nian").attr("action","index.php?action=Salary&mode=sumNianSalary");
        $("#iform_nian").submit();
    }
    function nian_er(){
        $("#iform_er").attr("action","index.php?action=Salary&mode=sumErSalary");
        $("#iform_er").submit();
    }
    function changeSum () {
        var val = $("#change").val();
        if (val == 1){
            $("#first").show(),$("#nian").hide(),$("#second").hide();
        } else if(val == 2) {
            $("#first").hide();$("#nian").show();$("#second").hide();
        } else if(val == 3) {
            $("#first").hide();$("#nian").hide();$("#second").show();
        }

    }
</script>
<script src="common/hot-js/handsontable.full.js"></script>
<script src="common/common-js/salaryNew.js"></script>
<link rel="stylesheet" media="screen" href="common/hot-js/handsontable.full.css">
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Product&mode=productUpload">产品导入</a>
            <a href="#" class="current">查看导入文件  </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="controls">
                        <form>
                        <span style="font-size: 12px; color: red">如果选择多项请用"+"号隔开</span>
                        <p>注：<input type="hidden" id="fileName" value="<?php echo $fname;?>"/>
                            <select id="change" onchange="changeSum()" disabled>
                                <option value="1">一次工资</option>
                                <option value="2">年终奖</option>
                                <option value="3">二次工资</option>
                            </select>
                            <div>公司名称：<label><?php echo $salTime['company_name'];?></label>工资月份：<label><?php echo $salTime['salaryTime'];?></label></div>

                            <input id="salTimeId" type="hidden" value="<?php echo $salTime['id'];?>"/>

                        </p>

                        </form>
                        <!--功能项-->
                        <div id="first" class="manage"
                             style="word-wrap: break-word; background-color: Tan;display: block;">
                            <form enctype="multipart/form-data" id="iform" action="" method="post">
                                选择身份证：<input type="text" id="shenfenzheng" value="2" style="width: 30"/>
                                选择相加项：<input type="text" id="add" value="3" style="width: 30"/>
                                选择相减项：<input type="text" id="del" value="" style="width: 30"/>
                                免税项：<input type="text" id="freeTex" type="hidden" name="sDate" id="sDate" value="" />
                                <input type="button" value="普通工资计算" id="sumFirst" /></font>
                                <font color="green"></font>
                            </form>
                        </div>
                        <div id="nian" class="manage"
                             style="word-wrap: break-word; background-color: red;display: none;">
                            <form enctype="multipart/form-data" id="iform_nian"
                                  action="index.php?action=Salary&mode=sumSalary" method="post">
                                选择身份证：<input type="text" name="shenfenzheng_nian" value="3" style="width: 30"/>
                                发年终奖月份（2012-01-01）：<input type="text" name="salaryTime_nian" value="" />
                                年终奖项：<input type="text" name="nian" value="4" style="width: 30"/>
                                是否做过本月一次工资：<select name="isFirst">
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                </select>
                                <input type="button" value="年终奖计算" onclick="nian_b()" />
                                <font color="red"><?php if($errorMsg)echo $errorMsg?></font>
                                <font color="green"><?php if($succ)echo $succ?></font>
                            </form>
                        </div>
                        <div id="second" class="manage"
                             style="word-wrap: break-word; background-color: yellow;display: none;">
                            <form enctype="multipart/form-data" id="iform_er"
                                  action="index.php?action=Salary&mode=sumSalary" method="post">
                                选择身份证：<input type="text" name="shenfenzheng_er" value="3" style="width: 30"/>
                                二次工资月份（2012-01-01）：<input type="text" name="salaryTime_er"
                                                          value="<?php if($salDate)echo $salDate;?>" /> 相加项：<input
                                    type="text" name="add" value="4" style="width: 30"/> <input type="hidden" value=""
                                                                                                id="datas" name="datas[]" /> <input type="hidden" name="comId"
                                                                                                                                    id="comId" value="<?php if($companyId)echo $companyId;?>" /> <input
                                    type="hidden" name="sDate" id="sDate"
                                    value="<?php if($salDate)echo $salDate;?>" /> <input type="hidden"
                                                                                         name="salType" id="salType"
                                                                                         value="<?php if($checkType)echo $checkType;?>" /> <input
                                    type="hidden" name="companyName" id="companyName"
                                    value="<?php if($companyName)echo $companyName;?>" /> <input
                                    type="button" value="二次工资计算" onclick="nian_er()" /> <font
                                    color="red"><?php if($errorMsg)echo $errorMsg?></font> <font
                                    color="green"><?php if($succ)echo $succ?></font>
                            </form>
                        </div>
                    </div>

                    <div class="span12" style="margin-left:0;">
                        <div class="widget-box">
                            <div class="tab-content">
                                <div>
                                    <div class="controls">
                                        <!-- checked="checked"-->
                                        <input type="button" value="重置" class="btn btn-primary" id="reload" />
                                    </div>
                                    <div id="exampleGrid" class="dataTable" style="width: 1400px; height: 200px; overflow: auto"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="span12" style="margin-left:0;">
                        <div class="widget-box">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="active"><a href="#home">计算结果</a></li>
                                <li><a href="#profile">错误信息<em style="color: red" id="error"></em></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="home">
                                    <div class="controls">
                                        <!-- checked="checked"-->
                                        <input type="checkbox" id="colHeaders" autocomplete="off"> <span>锁定前两列</span>
                                        <input type="button" value="保存工资" class="btn btn-success" id="save" />
                                    </div>
                                    <div id="sumGrid" class="dataTable" style="width: 1400px; height: 400px; overflow: auto"></div>
                                </div>
                                <div class="tab-pane" id="profile">
                                    <table id="errorInfo">

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal hide" id="modal-event1">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>保存工资</h3>
    </div>
    <form action="" id="company_validate" method="post" class="form-horizontal"  novalidate="novalidate">
        <div class="modal-body">
            <div class="designer_win">
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>所属公司：<?php echo $salTime['company_name'];?><input type="hidden" value="" id="company_id" name="company_id"/></div>
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>工资月份：
                    <leab><?php echo $salTime['salaryTime'];?></leab>
                </div>
                <div class="tips">备注：<textarea id="mark">

                    </textarea></div>
            </div>
        </div>

        <div class="modal-footer modal_operate">
            <button type="button" id="salarySave" class="btn btn-primary">保存</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </form>
    <div class="search_suggest" id="custor_search_suggest">
        <ul class="search_ul">

        </ul>
        <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
    </div>
</div>