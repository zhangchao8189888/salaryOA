<?php
/* @var $this JController */
$this->pageTitle = ($id > 0)?"编辑专题":"创建专题";
$files=$form_data['files'];
?>

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
            <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="/special/">产品导入</a>
            <a href="/special/edit/<?php echo ($id > 0)?$id:'' ; ?>  " class="current">创建出库  </a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>产品导入 </h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post" action="index.php?action=Admin&mode=upload" enctype="multipart/form-data" name="basic_validate" id="basic_validate" novalidate="novalidate">
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
                            <input type="text" id="start_date" name="start_date" value="<?php echo $start_date?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true})"/>&nbsp;至&nbsp;<input type="text" id="end_date" name="end_date" value="<?php echo $end_date?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true})"/>
                            <button type="submit" class="btn btn-primary" id="btn_submit">确定</button>
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

                            <td><div><a href="index.php?action=Admin&mode=excelToHtml&fname=<?php echo $row;?>">查看</a>|<a href="index.php?action=Admin&mode=to	Update&fname=<?php echo $row;?>">批量修改</a>|<a href="index.php?action=Admin&mode=del&fname=<?php echo $row;?>">删除</a><!-- <a  onclick="rename('<?php echo $row;?>');">重命名</a> --></div></td>


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