<?php
$jingbanList=$form_data['jingbanList'];
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
            <a href="#" class="current">客户经理</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12"><div class="widget-box">
                    <div class="widget-title">
                        <ul class="nav nav-pills">
                            <li class=""><a href="index.php?action=Customer&mode=getCustomerList">客户列表</a></li>
                            <li class="active"><a href="index.php?action=Customer&mode=getJingbanrenlist">客户经理</a></li>
                            <li class=""><a href="index.php?action=Customer&mode=toAddCustomerLevel" >客户级别设置</a></li>
                        </ul>

                    </div>
                    <div class="widget-content tab-content ">
                        <div class="actions">
                            <div class="control-group">
                                <div class="controls">
                                    <div style="float: right;margin-right: 20px">
                                        <a id="level_add" event_type="check" data-toggle="modal" href="#modal-event" class="btn btn-success" >新增客户经理</a>
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
                                    <th class="tl"><div>姓名</div></th>
                                    <th class="tl"><div>类别</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">

                                <?php
                                while ($row = mysql_fetch_array($jingbanList)){
                                    ?>
                                    <tr class="">
                                        <td class="tl pl10"></td>
                                        <td class="tl pl10">
                                            <div><a href="#"  class="serial modifyLevel" data-id="<?php echo $row['id'];?>" data-type="<?php echo $row['jingbanren_type'];?>"><?php echo $row['jingbanren_name'];?></a>
                                            </div>
                                        </td>
                                        <td class="tl pl10"><?php if ($row['jingbanren_type'] == 1){echo '店员'; }elseif($row['jingbanren_type'] == 2) {echo '销售经理';}?></td>

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
        <h3>添加客户经理</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <div class="tips">姓名：<input type="text" maxlength="20" id="c_name"  ></div>
            <div class="tips">类别：<select id="c_type">
                    <option value="1">店员</option>
                    <option value="2">销售经理</option>
                </select></div>
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
        <h3>修改客户经理</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <div class="tips">级别名称：<input type="text" maxlength="20" id="cName"  ></div>
            <div class="tips">类别：<select id="cType">
                        <option value="1">店员</option>
                        <option value="2">销售经理</option>
                    </select></div>
            <input type="hidden" id="cId"  >
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
            var cName = $(this).text();
            var cType = $(this).attr("data-type");
            var id = $(this).attr("data-id");
            $("#cId").val(id);
            $("#cName").val(cName);
            $("#cType").val(cType);
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
                        url: "index.php?action=Customer&mode=updateJingban",
                        async:false,
                        data: {
                            id: $("#cId").val(),
                            c_name: $("#cName").val(),
                            c_type: $("#cType").val()
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
                    url: "index.php?action=Customer&mode=addJingban",
                    async:false,
                    data: {
                        c_name: $("#c_name").val(),
                        c_type: $("#c_type").val()
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
                    url: "index.php?action=Customer&mode=delJingbanList",
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


