<?php
$errorMsg=$form_data['error'];
$salaryDate=$form_data['salaryDate'];
$comName=$form_data['comName'];
$department_id=$form_data['department_id'];
$ziduanIds=$form_data['ziduanIds'];
?>

<script language="javascript"  type="text/javascript">
    $(function(){
        $('#myTab a').click(function (e) {
            e.preventDefault();//阻止a链接的跳转行为
            $(this).tab('show');//显示当前选中的链接及关联的content
        })
        $('#test').bind('input propertychange', function() {
            alert("aa");
            $('#content').html($(this).val().length + ' characters');
        });
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
<script src="common/common-js/salary.js"></script>
<link rel="stylesheet" media="screen" href="common/hot-js/handsontable.full.css">
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Product&mode=productUpload">做工资</a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="manage">
                        <div><span style="font-size: 16px; color: #008000">公司单位：<?php echo $comName;?> 工资月份：<?php echo $salaryDate;?>月</span></div>
                        <p>注：
                            <input type="hidden" id="ziduanIds" value="<?php echo $ziduanIds;?>"/>
                            <input type="hidden" id="department_id" value="<?php echo $department_id;?>"/>
                            <input type="hidden" id="salaryDate" value="<?php echo $salaryDate;?>"/>
                        </p>
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
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>所属公司：
                    <input type="text" maxlength="20" id="e_company"name="e_company" autocomplete="off" value="<?php echo $comName;?>" readonly />
                    <input type="hidden" id="company_id" name="company_id" value="<?php echo $company_id;?>"/></div>
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>工资月份：
                    <input type="text" id="salaryDate" name="salaryDate" value="<?php echo $salaryDate;?>" />
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