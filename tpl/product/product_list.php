<?php
$proList=$form_data['proList'];
$total=$form_data['total'];
$proName=$form_data['proName'];
$type=$form_data['type'];
$searchKey=$form_data['searchKey'];
$admin=$_SESSION['admin'];
$isClose = true;
if (!empty($proName) || !empty($type) || !empty($searchKey)) {
    $isExpland = false;
}
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
            <a href="index.php?action=Customer&mode=getCustomerList">商品</a>
            <a href="#" class="current">商品列表</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12"><div class="widget-box">
                    <div class="widget-title">
                        <ul class="nav nav-pills">
                            <li class="active"><a href="index.php?action=Product&mode=getProductList">商品列表</a></li>
                            <li class=""><a href="index.php?action=Product&mode=getProductNumList">商品库存</a></li>
                        </ul>

                    </div>

                    <?php if (!empty($error)) {?>
                        <div class="alert alert-error">
                            <button data-dismiss="alert" class="close">×</button>
                            <strong>添加失败!</strong> <?php echo $error;?> </div>
                    <?php }?>
                    <div class="widget-content tab-content ">
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <div class="widget-title"> <a data-parent="#collapse-group" href="#collapseGTwo" data-toggle="collapse" class="collapsed"> <span class="icon"><i class="icon-circle-arrow-right"></i></span>
                                        <h5>高级搜索</h5>
                                    </a> </div>
                            </div>
                            <div class="accordion-body collapse" id="collapseGTwo" style="height: 0px;">
                                <form action="index.php?action=Product&mode=getProductList" method="post">
                                <div class="form-actions">
                                    <div class="control-group">
                                    <span class="pull-left">
                                        <label class="control-label">关键字：</label>
                                        <div class="controls">
                                            <input name="searchKey" id="searchKey" type="text" value="<?php echo $searchKey;?>" style="width: 100px"/>　
                                        </div>
                                    </span><span class="pull-left">
                                        <label class="control-label">产品名称：</label>
                                        <div class="controls">
                                            <input name="proName" id="proName" type="text" value="<?php echo $proName;?>" style="width: 100px"/>　
                                        </div>
                                    </span>
                                    <span class="pull-left">
                                        <label class="control-label">商品类别：</label>
                                        <div class="controls">
                                            <input type="text" id="type" name="type" value="<?php echo $type?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM'})"/>　
                                            <input type="submit" value="查询商品列表" class="btn btn-success"/>
                                        </div>

                                    </span>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                        <div class="actions">
                            <div class="control-group">
                                <div class="controls">
                                    <div style="float: right;margin-right: 20px">
                                        <a id="level_add" event_type="check" href="index.php?action=Product&mode=toAdd" class="btn btn-success" >新增商品</a>
                                    </div>
                                    <div style="float: right;margin-right: 60px">
                                        <a id="level_add" event_type="check" href="index.php?action=Product&mode=productUpload" class="btn btn-success" >导入</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane active" id="tab1">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <!---->
                                    <th class="tl"><div></div></th>
                                    <th class="tl"><div>商品编号</div></th>
                                    <th class="tl"><div>供应商</div></th>
                                    <th class="tl"><div>货品简称</div></th>
                                    <th class="tl"><div>货品类别</div></th>
                                    <th class="tl"><div>单位</div></th>
                                    <th class="tl"><div>有无折扣</div></th>
                                    <!--<th class="tl"><div>数量</div></th>-->
                                    <th class="tl"><div>市场定价</div></th>
                                    <th class="tl"><div>入库时间</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">

                                <?php
                                global $customerType;
                                while ($row=mysql_fetch_array($proList) ){
                                    ?>
                                    <tr >
                                        <td><div>
                                                <input type="checkbox" name="orderList" value="<?php echo $row['id'];?>"   />
                                            </div></td>
                                        <td><div><a href="index.php?action=Product&mode=getProduct&pid=<?php  echo $row['id'];?>" target="_self"><?php if (!empty($row['pro_code'])) echo $row['pro_code']; else echo '<span style="color: red">无编号</span>';?></a></div></td>
                                        <td><div><?php echo $row['pro_supplier'];?></div></td>
                                        <td><div><?php echo $row['pro_name'];?></div></td>
                                        <td><div><?php echo $row['pro_type'];?></div></td>
                                        <td><div><?php echo $row['pro_unit'];?></div></td>
                                        <td><div><?php if($row['pro_flag']==0){echo '无折扣';} elseif($row['pro_flag']==1){echo '有折扣';}?></div></td>
                                        <!--<td><div><?php /*echo $row['pro_num'];*/?></div></td>-->
                                        <td><div><?php echo $row['pro_price'];?></div></td>
                                        <td><div><?php echo date('Y年n月j日',strtotime($row['add_time']));?></div></td>
                                        <td><div><?php if($admin['admin_type']==1||$admin['admin_type']==3){?><a href="#" onclick="del(<?php  echo $row['id'];?>)" target="_self">删除</a><?php }?></div></td>

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


