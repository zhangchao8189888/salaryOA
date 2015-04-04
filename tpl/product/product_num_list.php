<?php
$proNumList=$form_data['proNumList'];
$admin=$_SESSION['admin'];
$total=$form_data['total'];
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
                            <li class=""><a href="index.php?action=Product&mode=getProductList">商品列表</a></li>
                            <li class="active"><a href="index.php?action=Product&mode=getProductNumList">商品库存</a></li>
                        </ul>

                    </div>
                    <div class="widget-content tab-content ">
                        <!--<div class="actions">
                            <div class="control-group">
                                <div class="controls">
                                    <div style="float: right;margin-right: 20px">
                                        <a id="level_add" event_type="check" data-toggle="modal" href="#modal-event" class="btn btn-success" >新增客户</a>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <div class="tab-pane active" id="tab1">

                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <!--单号/下单时间 	代理商名称 	金额 	出库/发货 	状态 	操作-->
                                    <th class="tl"><div>类别</div></th>
                                    <th class="tl"><div>商品代码</div></th>
                                    <th class="tl"><div>商品名称</div></th>
                                    <th class="tl"><div>库存</div></th>
                                    <th class="tl"><div>单位</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">

                                <?php
                                foreach ($proNumList as $row){
                                    ?>
                                    <tr class="">
                                        <td class="tl pl10"><?php echo $row['pro_type'];?></td>
                                        <td class="tl pl10">
                                            <div><a href="#"  class="serial modifyLevel" "><?php echo $row['pro_code'];?></a>
                                            </div>
                                        </td>
                                        <td class="tl pl10"><?php echo $row['pro_name'];?></td>
                                        <td class="tl pl10"><?php echo $row['pro_num'];?></td>
                                        <td class="tl pl10"><?php echo $row['pro_unit'];?></td>

                                        <td class="tr">
                                            <a title="修改库存" href="#" class="btn btn-success btn-small modifyProNum" data-name="<?php echo $row['pro_name'];?>"data-num="<?php echo $row['pro_num'];?>" data-code="<?php echo $row['pro_code'];?>" data-id="<?php echo $row['pro_id'];?>">修改库存</a>
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
        </div>
    </div>
</div>
<div class="modal hide" id="modal-event2">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>修改商品库存</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <div class="tips">商品名称：<input type="text" maxlength="20" id="proName"  readonly></div>
            <div class="tips">商品编码：<input type="text" maxlength="20" id="proCode"  readonly></div>
            <div class="tips">数量：<input type="text" maxlength="20" id="proNum"  ></div>
            <input type="hidden" id="proId"  >
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
        $(".modifyProNum").click(function(){
            var proName = $(this).attr("data-name");
            var proCode = $(this).attr("data-code");
            var proNum = $(this).attr("data-num");
            var id = $(this).attr("data-id");
            $("#proId").val(id);
            $("#proName").val(proName);
            $("#proCode").val(proCode);
            $("#proNum").val(proNum);
            $('#modal-event2').modal({show:true});
        });

        $("#num_modify").click(
            function(){
                $.ajax(
                    {
                        type: "POST",
                        url: "index.php?action=Product&mode=updateProductNum",
                        async:false,
                        data: {
                            id: $("#proId").val(),
                            proNum: $("#proNum").val()
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


    });
</script>


