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
$proTime=$form_data['proTime'];
$pro_date=$form_data['pro_date'];
$order=$form_data['order'];

$admin=$_SESSION['admin'];
?>
<script language="javascript" type="text/javascript">
    $(function(){
        $("#pro_add").click(function(){
            $("#pro_date").val($("#shaijia_date").val());
            $('#modal-event1').modal({show:true});
        });
        $("#num_add").click(function(){
            $.ajax(
                {
                    type: "POST",
                    url: "index.php?action=Kucun&mode=addShaijiaNum",
                    data: "pro_id="+$("#pro_id").val()+"&pro_code="+$("#pro_code").val()
                        +"&pro_date="+$("#pro_date").val()+"&yuechu="+$("#yuechu").val()+"&sunhao="+$("#sunhao").val()+"&kuisun="+$("#kuisun").val(),
                    dataType: "json",
                    success: function(msg)
                    {
                        if (msg.code =='1000'){
                            if (confirm("保存成功，继续添加？")){

                            } else {
                                location.href = "index.php?action=Kucun&mode=toShaijiaList";
                            }
                        }
                    }
                }
            );
        });
    });
    function getProByName(){
        $("#proList").html("");
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Kucun&mode=getProListByName",
                data: "proNo="+$("#proNo").val(),
                dataType: "json",
                success: function(msg)
                {
                    // alert(result);
                    result = msg;
                    $("#proList").append("<option value='-1' id='selectPro'  selected='selected'>选择商品信息列表</option>");

                    for(var i=0;i<result.length;i++){
                        var obj=result[i];

                        $("#proList").append(" <option value="+obj.id+"  >"+obj.pro_code+"</option>");
                    }
                    $("#proList").val(-1);
                }
            }
        );
    }
    function writeProName(){
        $.ajax(
            {
                type: "POST",
                url: "index.php?action=Kucun&mode=getProById&type=shaijia",
                data: "proId="+$("#proList option:selected").val()+"&pro_date="+$("#pro_date").val(),
                dataType: "json",
                success: function(msg)
                {
                    prodcut=msg;
                    $("#pro_id").val(prodcut.pro_id);
                    $("#pro_code").val(prodcut.pro_code);
                    $("#yuechu").val(prodcut.yuechu_shaijia);
                    $("#sunhao").val(prodcut.shaijia_sunhao);
                    $("#kuisun").val(prodcut.pandian_yingkui);
                }
            }
        );
        $("#proNo").val($("#proList option:selected").text());
        // Math.ceil(7/2)
    }
    function sortPro() {
        location.href = "index.php?action=Kucun&mode=toShaijiaList&proNo="
            +$("#proNo_search").val()+"&produce_date="+$("#produce_date").val()+"&order="+$("#proSort").val();
    }
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Admin&mode=filesUpload">库存</a>
            <a href="#" class="current">入库列表</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>入库列表 </h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post" action="index.php?action=Kucun&mode=toShaijiaList" enctype="multipart/form-data" name="basic_validate" id="basic_validate" novalidate="novalidate">
                            <div class="control-group" id="createError" style="display:none;">
                                <label class="control-label">&nbsp;</label>
                                <div class="controls">
                                    <span class="colorRem"></span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="control-group">
                                    <span class="pull-left">
                                        <label class="control-label">排序规则：</label>
                                        <div class="controls">
                                            <select id="proSort" onchange="sortPro()" >
                                                <option value = "1" <?php if($order ==1) echo "selected" ?>>产品型号</option>
                                                <option value = "2" <?php if($order ==2) echo "selected" ?>>入库小计</option>
                                                <option value = "3" <?php if($order ==3) echo "selected" ?>>更新时间</option>
                                            </select>
                                        </div>

                                    </span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="control-group">
                                    <span class="pull-left">
                                        <div class="controls">
                                            <a class="btn btn-warning btn-small" id="pro_add" event_type="check" data-toggle="modal" href="#modal-event" data-adress="/fuser/upstatus">添加</a>
                                        </div>
                                    </span>
                                    <span class="pull-left">
                                        <label class="control-label">产品型号：</label>
                                        <div class="controls">
                                            <input name="proNo" id="proNo_search" type="text" value="<?php echo $proNo;?>" style="width: 100px"/>　
                                        </div>
                                    </span>
                                    <span class="pull-left">
                                        <label class="control-label">晒架月份：</label>
                                        <div class="controls">
                                            <input type="text" id="shaijia_date" name="shaijia_date" value="<?php echo $pro_date?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM'})"/>　
                                            <input type="submit" value="查询入库列表" class="btn btn-success"/>
                                        </div>

                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th><div>商品型号</div></th>
                        <th><div>月初晒架</div></th>
                        <th><div>生产小计</div></th>
                        <th><div>入库数小计</div></th>
                        <th><div>晒架损耗</div></th>
                        <th><div>盘点盈亏</div></th>
                        <th><div>现在晒架</div></th>
                    </tr>
                    </thead>
                    <tbody  id="tbodays">

                    <?php
                    global $customerType;
                    foreach ($prolist as $row){
                        ?>
                        <tr >
                            <td><div><a href="index.php?action=Product&mode=getProduct&pid=<?php  echo $row['id'];?>" target="_self"><?php echo $row['pro_code'];?></a></div></td>
                            <td><div><span ><?php if ($row['yuechu_shaijia'])echo $row['yuechu_shaijia']; else echo 0;?></span></div></td>
                            <td><div><span style='color: lightseagreen'><?php if ($row['produce_num_total'])echo $row['produce_num_total']; else echo 0;?></span></div></td>
                            <td><div><span style='color: lightseagreen'><?php if ($row['intoStorage_num_total'])echo $row['intoStorage_num_total']; else echo 0;?></span></div></td>
                            <td><div><span ><?php if ($row['shaijia_sunhao'])echo $row['shaijia_sunhao']; else echo 0;?></span></div></td>
                            <td><div><span ><?php if ($row['pandian_yingkui'])echo $row['pandian_yingkui']; else echo 0;?></span></div></td>
                            <td><div><span style='color: #149bdf'><?php if ($row['pandian_yingkui'])echo $row['now_num_total']; else echo 0;?></span></div></td>

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
<div class="modal hide" id="modal-event1">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>添加生产数量</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <div class="tips">产品型号：<input type="text" maxlength="20" id="proNo"  ><input type="button" value="查询" onclick="getProByName()"  id="botton" /></div>
            <div class="controls">
                <select id="proList"  size="8" onchange="writeProName()" >
                </select>
                <input type="hidden" value="" id="pro_id"/>
                <input type="hidden" value="" id="pro_code"/>

            </div>
            <div class="tips">晒架月份：<input type="text" id="pro_date" name="pro_date" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM'})"/></div>
            <div class="tips">月初晒架：<input type="text" maxlength="20" id="yuechu" name="yuechu" ></div>
            <div class="tips">晒架损耗：<input type="text" maxlength="20" id="sunhao" name="sunhao" ></div>
            <div class="tips">盘点亏损：<input type="text" maxlength="20" id="kuisun" name="kuisun" ></div>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn btn-primary" id="num_add">确定</a>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>

