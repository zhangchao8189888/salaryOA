<?php
$prolist=$form_data['proList'];
$total=$form_data['total'];
$pageindex=$form_data['pageindex'];
$pagesize=$form_data['pagesize'];
$warn=$form_data['warn'];
$succ=$form_data['succ'];
$proNo=$form_data['proNo'];
$group=$form_data['group'];
$proSpec=$form_data['proSpec'];

$admin=$_SESSION['admin'];
?>
<script language="javascript" type="text/javascript" src="common/js/jquery_last.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="common/js/jquery.pagination.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript">
    function del(cid){
        if(confirm('确定要删除该客户信息吗?')){
            $("#iform").attr("action","index.php?action=Product&mode=delProduct");
            $("#pid").val(cid)
            $("#iform").submit();
        }
    }
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Admin&mode=filesUpload">库存</a>
            <a href="#" class="current">产品列表</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>产品列表 </h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post" action="index.php?action=Admin&mode=productImport" enctype="multipart/form-data" name="basic_validate" id="basic_validate" novalidate="novalidate">
                            <div class="control-group" id="createError" style="display:none;">
                                <label class="control-label">&nbsp;</label>
                                <div class="controls">
                                    <span class="colorRem"></span>
                                </div>
                            </div>
                            <div class="form-actions">

                            </div>
                        </form>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th><div>商品ID</div></th>
                            <th><div>商品型号</div></th>
                            <th><div>商品规格</div></th>
                            <th><div>单位</div></th>
                            <th><div>组别</div></th>
                            <th><div>操作</div></th>
                        </tr>
                        </thead>
                        <tbody  id="tbodays">

                        <?php
                        global $customerType;
                        while ($row=mysql_fetch_array($prolist) ){
                            //var_dump($row);
                            /**
                             * `pro_code` varchar(100) DEFAULT NULL,
                            `pro_spec` varchar(200) DEFAULT NULL,
                            `pro_unit` varchar(50) DEFAULT NULL,
                            `pro_group` varchar(255) DEFAULT NULL,
                            `add_time` date DEFAULT NULL,
                             */
                            ?>
                            <tr >
                                <td><div><?php echo $row['id'];?></div></td>
                                <td><div><a href="index.php?action=Product&mode=getProduct&pid=<?php  echo $row['id'];?>" target="_self"><?php echo $row['pro_code'];?></a></div></td>
                                <td><div><?php echo $row['pro_spec'];?></div></td>
                                <td><div><?php echo $row['pro_unit'];?></div></td>
                                <td><div><?php echo $row['pro_group'];?></div></td>
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
