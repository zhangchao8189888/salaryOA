<?php
$error=$form_data['error'];
$succ=$form_data['succ'];
$errorList=$form_data['errorlist'];
$emList=$form_data['emList'];
?>
<script type="text/javascript">
    $(function(){
        $('#test').bind('input propertychange', function() {
            alert("aa");
            $('#content').html($(this).val().length + ' characters');
        });
    });
    function chanpinDownLoad(){
        $("#iform").attr("action","index.php?action=Employ&mode=getEmployTemlate");
        //$("#nfname").val($("#newfname").val());
        $("#iform").submit();
    }
</script>
<script src="common/common-js/employImport.js"></script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">员工导入</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>员工导入 </h5>
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
                                <input type="button" value="下载员工导入模版" onclick="chanpinDownLoad()" class="btn btn-primary"/>
                            </form>

                        </div>
                        <form class="form-horizontal" method="post" action="index.php?action=Employ&mode=newemImport" enctype="multipart/form-data" name="basic_validate" id="basic_validate" novalidate="novalidate">
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

                                <input type="button" value="导入" class="btn btn-success" id="submitBtn1" >
                                <div class="search_suggest" id="custor_search_suggest">
                                    <ul class="search_ul">

                                    </ul>
                                    <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div style="min-width:830px">
                        <font color="red">员工导入错误信息列表</font>
                        <table id="tab_list" class="table table-bordered table-striped" width="50%">

                            <tr>
                                <th><div>员工名称</div></th>
                                <th><div>身份证号</div></th>
                                <th><div>错误信息</div></th>
                            </tr>

                            <?php foreach ($errorList as $row){?>
                                <tr >

                                    <td><div><?php echo $row['e_name'];?></div></td>
                                    <td><div><?php echo $row['e_num'];?></div></td>
                                    <td><div><?php echo $row['errmg'];?></div></td>

                                </tr>
                            <?php }?>

                        </table>
                        <font color="green">员工导入成功列表(<?php echo count($emList);?>)</font>
                        <table id="tab_list" class="table table-bordered table-striped" width="50%">

                            <tr>
                                <th><div>员工名称</div></th>
                                <th><div>身份证号</div></th>
                            </tr>

                            <?php foreach ($emList as $row){?>
                                <tr >

                                    <td><div><?php echo $row['e_name'];?></div></td>
                                    <td><div><?php echo $row['e_num'];?></div></td>

                                </tr>
                            <?php }?>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="common/js/datepicker/WdatePicker.js"></script>