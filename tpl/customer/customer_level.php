<?php
$levelList=$form_data['levelList'];
$admin=$_SESSION['admin'];
?>
<style type="text/css">
    .actions {
        margin-bottom: 50px;
        margin-top: 0px;
        padding: 19px 20px 0;
    }
</style>
<script language="javascript" type="text/javascript">
    $(function(){
        $(".btn-success").click(function(){
            $().submit();
        });
    });
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Customer&mode=getCustomerList">客户</a>
            <a href="#" class="current">客户级别设置</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12"><div class="widget-box">
                    <div class="widget-title">
                        <ul class="nav nav-pills">
                            <li class=""><a href="index.php?action=Customer&mode=getCustomerList">客户列表</a></li>
                            <li class=""><a href="index.php?action=Customer&mode=getJingbanrenlist">客户经理</a></li>
                            <li class="active"><a href="index.php?action=Customer&mode=toAddCustomerLevel" >客户级别设置</a></li>
                        </ul>

                    </div>
                    <div class="widget-content tab-content ">
                        <div class="actions">
                            <div class="control-group">
                                    <div class="controls">
                                        <div style="float: right;margin-right: 20px">
                                            <a id="level_add" event_type="check" data-toggle="modal" href="#modal-event" class="btn btn-success" >新增客户</a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="tab-pane active" id="tab1">

                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <!--单号/下单时间 	代理商名称 	金额 	出库/发货 	状态 	操作-->
                                    <th class="tl"><div></div></th>
                                    <th class="tl"><div>客户级别</div></th>
                                    <th class="tl"><div>订货折扣</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">

                                <?php
                                while ($row = mysql_fetch_array($levelList)){
                                    ?>
                                    <tr class="">
                                        <td class="tl pl10"></td>
                                        <td class="tl pl10">
                                            <div><a href="#"  class="serial modifyLevel" data-id="<?php echo $row['id'];?>" data-discount="<?php echo $row['discount'];?>"><?php echo $row['level_name'];?></a>
                                            </div>
                                        </td>
                                        <td class="tl pl10"><?php echo $row['discount'];?>%</td>

                                        <td class="tr">
                                            <a title="删除" href="#" class="btn btn-success btn-small delLevel" data-id="<?php echo $row['id'];?>">删除</a>
                                        </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
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
        <h3>添加客户级别</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <div class="tips">级别名称：<input type="text" maxlength="20" id="level_name"  ></div>
            <div class="tips">折扣：<input type="text" maxlength="20" id="discount"  >%</div>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="num_add">确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<div class="modal hide" id="modal-event2">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>修改客户级别</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <div class="tips">级别名称：<input type="text" maxlength="20" id="levelName"  ></div>
            <div class="tips">折扣：<input type="text" maxlength="20" id="levelDiscount"  >%</div>
            <input type="hidden" id="levelId"  >
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="num_modify">修改</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
</div>
<script language="javascript" type="text/javascript">
    $(function(){
        $(".modifyLevel").click(function(){
            var levelName = $(this).text();
            var discounts = $(this).attr("data-discount");
            var id = $(this).attr("data-id");
            $("#levelId").val(id);
            $("#levelName").val(levelName);
            $("#levelDiscount").val(discounts);
            $('#modal-event2').modal({show:true});
        });

        $("#level_add").click(function () {

            $('#modal-event1').modal({show:true});
        });
        $("#num_modify").click(
            function(){
                $.ajax(
                    {
                        type: "POST",
                        url: "index.php?action=Customer&mode=updateCustomerLevel",
                        async:false,
                        data: {
                            id: $("#levelId").val(),
                            level_name: $("#levelName").val(),
                            discount: $("#levelDiscount").val()
                        },
                        dataType: "json",
                        success: function(data){
                            if (data.code > 100000) {
                                alert(data.message);
                                return;
                            } else {
                                window.location.reload();
                            }
                        }
                    }
                );
            }
        );
        $("#num_add").click(function(){
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Customer&mode=addCustomerLevel",
                    async:false,
                    data: {
                        level_name: $("#level_name").val(),
                        discount: $("#discount").val()
                    },
                    dataType: "json",
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.message);
                            return;
                        } else {
                            window.location.reload();
                        }
                    }
                }
            );
        });
        $(".delLevel").click(function(){
            var id= $(this).attr("data-id");
            if (!id) {
                return;
            }
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Customer&mode=delCustomerLevel",
                    async:false,
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.message);
                            return;
                        } else {
                            window.location.reload();
                        }
                    }
                }
            );
        });

    });
</script>


