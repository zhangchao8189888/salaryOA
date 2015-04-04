<?php
$custlist=$form_data['customerList'];
$total=$form_data['total'];
$error=$form_data['warn'];
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
            <a href="#" class="current">客户列表</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12"><div class="widget-box">
                    <div class="widget-title">
                        <ul class="nav nav-pills">
                            <li class="active"><a href="index.php?action=Customer&mode=getCustomerList">客户列表</a></li>
                            <li class=""><a href="index.php?action=Customer&mode=getJingbanrenlist">客户经理</a></li>
                            <li class=""><a href="index.php?action=Customer&mode=toAddCustomerLevel" >客户级别设置</a></li>
                        </ul>

                    </div>

                    <?php if (!empty($error)) {?>
                        <div class="alert alert-error">
                            <button data-dismiss="alert" class="close">×</button>
                            <strong>添加失败!</strong> <?php echo $error;?> </div>
                    <?php }?>
                    <div class="widget-content tab-content ">
                        <div class="actions">
                            <div class="control-group">
                                <div class="controls">
                                    <div style="float: right;margin-right: 20px">
                                        <a id="level_add" event_type="check" href="index.php?action=Customer&mode=toAdd" class="btn btn-success" >新增客户</a>
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
                                    <th class="tl"><div>客户编号</div></th>
                                    <th class="tl"><div>客户名称</div></th>
                                    <th class="tl"><div>客户经理</div></th>
                                    <th class="tl"><div>级别</div></th>
                                    <th class="tl"><div>联系人</div></th>
                                    <th class="tl"><div>联系方式</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">

                                <?php
                                foreach ($custlist as $row){
                                    ?>
                                    <tr class="">
                                        <td class="tl pl10"></td>
                                        <td class="tl pl10"><?php echo $row['custo_no'];?></td>
                                        <td class="tl pl10"><a href="index.php?action=Customer&mode=getCustomer&id=<?php echo $row['id'];?>" target="_blank" class="company"><?php echo $row['custo_name'];?></a></td>
                                        <td class="tr pr10"><?php echo $row['jinbanren_name']['jingbanren_name'];?></td>
                                        <td class="tr pr10"><?php echo $row['custo_level_name'];?></td>
                                        <td class="tr pr10"><?php echo $row['custoHaed_name'];?></td>
                                        <td class="tr pr10"><?php echo $row['moveTel_no'];?></td>
                                        <td class="tr">
                                            <a title="删除" href="#" class="btn btn-success btn-small delCustomer" data-id="<?php echo $row['id'];?>">删除</a>
                                            <a title="修改" href=href="index.php?action=Customer&mode=getCustomer&id=<?php echo $row['id'];?>" class="btn btn-success btn-small modifyCustomer" data-id="<?php echo $row['id'];?>">修改</a>

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
</div>
<script language="javascript" type="text/javascript">
    $(function(){
        $("#pro_add").click(function(){
            $("#pro_date").val($("#shaijia_date").val());
            $('#modal-event1').modal({show:true});
        });
    });
</script>


