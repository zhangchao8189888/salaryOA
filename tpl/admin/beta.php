<?php
/* @var $this JController */
$this->pageTitle = ($id > 0)?"编辑专题":"创建专题";
?>
<script type="text/javascript">
    $(function(){
        alert(1111);
    });
    function dataChange() {
        var  data = $("#test").val();
        $("#tbodays").append("<tr><td>"+data+"</td><td>标签</td><td>短链接（只能是数字或者字母）</td><td>操作</td></tr>");

    }
    function focusFun() {
        $("#test").val("");
        $("#test").focus();
    }
</script>
<div id="content-header">
    <div id="breadcrumb">
        <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="/special/">出库管理</a>
        <a href="/special/edit/<?php echo ($id > 0)?$id:'' ; ?>  " class="current">创建出库  </a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                    <h5>添加出库 </h5>
                </div>
                <div class="widget-content nopadding">
                    <form class="form-horizontal" method="post" action="/special/save" name="basic_validate" id="basic_validate" novalidate="novalidate">
                        <div class="control-group" id="createError" style="display:none;">
                            <label class="control-label">&nbsp;</label>
                            <div class="controls">
                                <span class="colorRem">请完整填写信息并上传头图后再进行发布~</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">编号：</label>
                            <div class="controls">
                                <input type="text" id="test" onchange="dataChange()" name="title" maxlength="20" value="<?php echo $dynamicInfo['title'];?>"   form_type="title">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">产品名称：</label>
                            <div class="controls">
                                <input name="url" id="pro"  onfocus="focusFun()" type="text" value="<?php echo $dynamicInfo['url'];?>" id="url"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">描述：</label>
                            <div class="controls">
                                <textarea name="desc" maxlength="140" form_type="textarea"><?php echo $dynamicInfo['description'];?></textarea></div>

                        </div>
                        <div class="control-group">
                            <label class="control-label">产品名称：</label>
                            <div class="controls">
                                <input name="url" type="text" value="<?php echo $dynamicInfo['url'];?>" id="url"/>
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" value="保存" class="btn btn-success" id="submitBtn1">
                        </div>
                    </form>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>标签</th>
                        <th>短链接（只能是数字或者字母）</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody  id="tbodays">


                    </tbody>
                    <tr class="tableCon"><td colspan="4" align="center">
                            <span class="pull-right"><input type="submit" name="allEdit" value="批量修改" class="btn btn-info" /></span></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
