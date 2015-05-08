<?php
$error=$form_data['error'];
$mess=$form_data['mess'];
$succ=$form_data['succ'];
$files=$form_data['files'];
session_start();
$user = $_SESSION ['admin'];
if ($user['user_type'] == 1)
{
    $company_id =  $user['user_id'];
    $company_name = $user['real_name'];
} elseif ($user['user_type'] == 3) {
    $company_id =  $user['company_id'];
    $company_name =  $user['company_name'];
}

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
<script type="text/javascript">
    $(function(){
        $('#test').bind('input propertychange', function() {
            alert("aa");
            $('#content').html($(this).val().length + ' characters');
        });
    });
    function chanpinDownLoad(){
        $("#iform").attr("action","index.php?action=Salary&mode=fileProDownload");
        //$("#nfname").val($("#newfname").val());
        $("#iform").submit();
    }
    function checkFiles (fname) {
        if (!$("#company_id").val()||!$("#salaryDate").val()) {
            alert('请先选择单位月份');
            return;
        }
        var href = 'index.php?action=Salary&mode=excelToHtml';
        $("#fname").val(fname);
        $("#salForm").attr('action',href);
        $("#salForm").submit();

    }
</script>
<script src="common/common-js/salaryUpload.js"></script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">工资导入</a>
        </div>
    </div>
    <div class="step step1">
        <div class="step-item step-item-1"><em class="ui-slide-gray-a current">一</em>导入文件</div>
        <div class="step-item step-item-2"><em class="ui-slide-gray-a">二</em>导入预览</div>
        <div class="step-item step-item-3"><em class="ui-slide-gray-a">三</em>导入完成</div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>工资导入 </h5>
                    </div>
                    <div class="widget-content nopadding">
                        <?php if (!empty($error)) {?>
                            <div class="alert alert-error">
                                <button data-dismiss="alert" class="close">×</button>
                                <strong>导入失败!</strong> <?php echo $error;?> </div>
                        <?php }?>
                        <?php if (!empty($succ)) {?>
                            <div class="alert alert-success">
                                <button data-dismiss="alert" class="close">×</button>
                                <strong>导入成功</strong> <?php echo $succ;?> </div>
                        <?php }?>
                        <?php if (!empty($mess)) {?>
                            <div class="alert alert-success">
                                <button data-dismiss="alert" class="close">×</button>
                                <strong>信息：</strong> <?php echo $mess;?> </div>
                        <?php }?>
                        <div class="form-actions">
                            <form id="iform" method="post">
                                <input type="button" value="下载工资导入模版" onclick="chanpinDownLoad()" class="btn btn-primary"/>
                            </form>
                            <form class="form-horizontal" method="post" action="index.php?action=Salary&mode=filesUp" enctype="multipart/form-data" name="basic_validate" id="basic_validate" novalidate="novalidate">
                                <div class="control-group" id="createError" style="display:none;">
                                    <label class="control-label">&nbsp;</label>
                                    <div class="controls">
                                        <span class="colorRem"></span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">导入文件：</label>
                                    <div class="controls"><input type="hidden" name="max_file_size" value="10000000"/>
                                        <input name="file"  type="file"/>　
                                    </div>
                                </div>

                            </form>
                            <div class="form-actions">
                                <form id="salForm" action="" method="post">
                                    <input type="submit" value="导入" class="btn btn-success" id="submitBtn1" >
                                    <div class="tips"><em style="color: red;padding-right: 10px;">*</em>所属公司：
                                        <input type="text" maxlength="20" id="e_company"name="e_company" value="<?php echo $company_name;?>" readonly autocomplete="off" />
                                        <input type="hidden" value="<?php echo $company_id;?>" id="company_id" name="company_id"/>
                                        <input type="hidden" value="" id="fname" name="fname"/>
                                    </div>
                                    <div class="tips"><em style="color: red;padding-right: 10px;">*</em>工资月份：
                                        <input type="text" id="salaryDate" name="salaryDate" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM',realDateFmt:'yyyy-MM'})"/>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>文件名</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody  id="tbodays">

                        <?php foreach ($files as $row){?>
                            <tr >

                                <td><div><?php echo $row;?></div></td>

                                <td><div><input type="button" class="btn btn-success"onclick="checkFiles('<?php echo $row;?>')" value="查看"/><!--|<a href="index.php?action=Product&mode=toUpdate&fname=<?php /*echo $row;*/?>">批量修改</a>-->|<a href="index.php?action=Salary&mode=fileDel&fname=<?php echo $row;?>">删除</a><!-- <a  onclick="rename('<?php echo $row;?>');">重命名</a> --></div></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="search_suggest" id="custor_search_suggest">
        <ul class="search_ul">

        </ul>
        <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
    </div>
</div>
<script type="text/javascript" src="common/js/datepicker/WdatePicker.js"></script>