<?php
$salaryTimeList=$form_data['salaryTimeList'];
$total=$form_data['total'];
$searchType=$form_data['searchType'];
$search_name = $form_data['search_name'];
$com_status = $form_data['com_status'];
$admin=$_SESSION['admin'];
?>
<script src="common/hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="common/hot-js/handsontable.full.css">
<script src="common/common-js/salaryList.js"></script>
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $('#myTab a').click(function (e) {
            e.preventDefault();//阻止a链接的跳转行为
            $(this).tab('show');//显示当前选中的链接及关联的content
        })

        $("#com_add").click(function(){
            $.ajax(
                {
                    type: "get",
                    url: "index.php?action=Company&mode=getCode",
                    data: {type:'fukuantongzhi'},
                    dataType: "json",
                    success: function(data){
                        $(".codeNo").text(data.codeNo);
                    }
                }
            );
            $('#modal-event1').modal({show:true});
        });
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
    <div class="step">
        <ul class="clearfix">
            <li class="s1 already">
                <div class="line"></div>
                <span class="mark" src="index.php?action=Employ&mode=toEmImport"></span>
                <span class="name">新增或导入员工信息</span>
            </li>
            <li class="s2 already">
                <div class="line"></div>
                <span class="mark" src="index.php?action=BaseData&mode=toSetSalZiduan"></span>
                <span class="name">录入工资单子项</span>
            </li>
            <li class="s3 already">
                <div class="line"></div>
                <span class="mark" src="index.php?action=Salary&mode=toMakeSalary"></span>
                <span class="name">做工资</span>
            </li>
            <li class="s4 already">
                <span class="mark" src="index.php?action=Salary&mode=salarySearchList"></span>
                <span class="name">工资查询</span>
            </li>
        </ul>
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
                                <form id="iForm" action="index.php?action=Company&mode=toCompanyList" method="post">
                                    <select id="searchType" name="searchType"   onchange="searchByType()" >
                                        <option value="name" <?php if ($searchType == 'name') echo 'selected'; ?>>企业名称</option>
                                        <option value="status" <?php if ($searchType == 'status') echo 'selected'; ?>>企业状态</option>
                                    </select>
                                    <input type="text" value="<?php echo $search_name;?>" name="search_name" id="search_name" placeholder="请输入企业名称"/>
                                    <select id="com_status" name="com_status"   onchange="searchByStatus()" style="display: none">
                                        <option value="1" <?php if ($com_status == '1') echo 'selected'; ?>>启用</option>
                                        <option value="0" <?php if ($com_status == '0') echo 'selected'; ?>>停用</option>
                                    </select>
                                    <input type="submit" value="查询"/>
                                    <input type="hidden" value="" id="salTimeId"name="salTimeId"/>
                                    <div style="float: right;margin-right: 20px"><a href="index.php?action=Salary&mode=toSalaryUpload"  class="btn btn-success" >新增工资</a></div>
                                </form>
                            </div>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <!-- 编号 企业名称 联系人 联系方式 地址 银行账户 客户等级 -->
                                    <th class="tl"><div></div></th>
                                    <th class="tl"><div>单位名称</div></th>
                                    <th class="tl"><div>工资月份</div></th>
                                    <th class="tl"><div>保存工资日期</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">
                                <?php
                                foreach ($salaryTimeList as $row){
                                ?>
                                <tr>
                                    <td class="tl pl10">
                                        <div></div>
                                    </td>
                                    <td class="tl pl10">
                                        <div><?php echo $row['salaryTime'];?></div>
                                    </td>
                                    <td class="tl pl10">
                                        <div><?php echo $row['company_name'];?></div>
                                    </td>
                                    <td class="tl pl10">
                                        <div><?php echo $row['op_salaryTime'];?></div>
                                    </td>
                                    <td class="tl pl10">
                                        <a title="查看" data-id="<?php echo $row['id'];?>" style="cursor:pointer" class="rowCheck theme-color">查看</a>
                                        <a title="继续添加" data-id="<?php echo $row['id'];?>" style="cursor:pointer" class="rowAdd theme-color">继续添加</a>
                                        <a title="删除" data-id="<?php echo $row['id'];?>" style="cursor:pointer" class="rowDell theme-color">删除</a>
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
            <div class="span12" style="margin-left:0;">
                <div class="widget-box">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#home">工资信息</a></li>
                        <li><a href="#bujiao">上个月漏交保险<em style="color: red" id="yanfuNum"></em></a></li>
                        <li><a href="#bukou">上个月垫付保险<em style="color: red" id="dianfuNum"></em></a></li>
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
                            <div id="exampleGrid" class="dataTable" style="width: 1000px; height: 400px; overflow: auto"></div>
                        </div>
                        <div class="tab-pane" id="bujiao">
                            <div id="dianfuGrid" class="dataTable" style="width: 1000px; height: 400px; overflow: auto"></div>
                        </div>
                        <div class="tab-pane" id="bukou">
                            <div id="yanfuGrid" class="dataTable" style="width: 1000px; height: 400px; overflow: auto"></div>
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


