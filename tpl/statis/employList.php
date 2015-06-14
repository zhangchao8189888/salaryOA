<?php
$department=$form_data['department'];
?>
<script src="common/hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="common/hot-js/handsontable.full.css">
<script src="common/common-js/employSalList.js"></script>
<script language="javascript" type="text/javascript">
    $(document).ready(function () {

    });
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">企业管理</a>
            <a href="#">企业信息</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12"><div class="widget-box">
                    <!--<div class="widget-title">
                        <ul class="nav nav-pills">
                            <li class="active"><a href="index.php?action=Order&mode=toOrderPage">订货单</a></li>
                            <li class=""><a href="index.php?action=Order&mode=toOrderReturnList">退货单</a></li>
                            <li class=""><a href="index.php?action=Order&mode=toOrderStatistics">订单商品统计</a></li>
                        </ul>

                    </div>-->

                    <div class="widget-content tab-content ">
                        <div class="tab-pane active">

                            <div class="controls">
                                <form id="iForm" action="index.php?action=Statis&mode=toEmployList" method="post">
                                    <?php
                                    $i = 1;
                                    foreach($department as $row){
                                        echo '<input name="departId" type="checkbox" value="'.$row['id'].'"  />'
                                            .$row['name'];
                                        if ($i%8 == 0) {
                                            echo "<br/>";
                                        }
                                        $i++;
                                    }
                                    ?>
                                    <div class="tips"><em style="color: red;padding-right: 10px;">*</em>起始月份：
                                        <input type="text" id="startDate" name="startDate" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM',realDateFmt:'yyyy-MM'})"/>
                                    </div>
                                    <div class="tips"><em style="color: red;padding-right: 10px;">*</em>结束月份：
                                        <input type="text" id="endDate" name="endDate" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM',realDateFmt:'yyyy-MM'})"/>
                                    </div>
                                    <input id="searchBtn" type="button" class="btn btn-success"  value="查询"/>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="span12" style="margin-left:0;">
                <div class="widget-box">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#home">工资信息</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="home">
                            <div class="controls">
                                <!-- checked="checked"-->
                                <form id="excelForm" method="post">
                                    <input type="hidden" name="salaryId" id="salaryId" value=""/>
                                </form>
                                <input type="checkbox" id="colHeaders" autocomplete="off"> <span>锁定前两列</span>
                                <input type="button" value="保存导出" class="btn btn-success" id="import" />
                            </div>
                            <div id="emSalGrid" class="dataTable" style="width: 900px; height: 400px; overflow: auto"></div>
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
    function searchByType () {
        var type = $("#searchType").val();
        if (type == 'name') {
            $("#search_name").show();
            $("#com_status").hide();
        } else if (type == 'status') {
            $("#search_name").hide();
            $("#com_status").show();
        }
    }
    function searchByStatus() {

    }
</script>


