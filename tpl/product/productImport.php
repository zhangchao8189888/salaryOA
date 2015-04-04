<?php
$error=$form_data['error'];
$succ=$form_data['succ'];
$files=$form_data['files'];

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
        $("#iform").attr("action","index.php?action=Admin&mode=fileProDownload");
        //$("#nfname").val($("#newfname").val());
        $("#iform").submit();
    }
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">产品导入</a>
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
                        <h5>产品导入 </h5>
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
                        <div class="form-actions">
                            <form id="iform" method="post">
                                <input type="button" value="下载产品模版" onclick="chanpinDownLoad()" class="btn btn-primary"/>
                            </form>

                        </div>
                        <form class="form-horizontal" method="post" action="index.php?action=Product&mode=filesUp" enctype="multipart/form-data" name="basic_validate" id="basic_validate" novalidate="novalidate">
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
                            <div class="form-actions">
                                <input type="submit" value="导入" class="btn btn-success" id="submitBtn1" >
                            </div>
                        </form>
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

                            <td><div><a href="index.php?action=Product&mode=excelToHtml&fname=<?php echo $row;?>">查看</a><!--|<a href="index.php?action=Product&mode=toUpdate&fname=<?php /*echo $row;*/?>">批量修改</a>-->|<a href="index.php?action=Product&mode=del&fname=<?php echo $row;?>">删除</a><!-- <a  onclick="rename('<?php echo $row;?>');">重命名</a> --></div></td>
                        </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="common/js/datepicker/WdatePicker.js"></script>