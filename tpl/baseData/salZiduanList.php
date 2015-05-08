<?php
$ziduanList=$form_data['ziduanList'];
?>
<script language="javascript" type="text/javascript">
    $(function(){

        $("#com_add").click(function(){
            $('#modal-event1').modal({show:true});
        });
    });
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">基础数据设置</a>
            <a href="#">工资字段</a>
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
            <li class="s3">
                <div class="line"></div>
                <span class="mark" src="index.php?action=Salary&mode=toMakeSalary"></span>
                <span class="name">做工资</span>
            </li>
            <li class="s4">
                <span class="mark" src="index.php?action=Salary&mode=salarySearchList"></span>
                <span class="name">工资查询</span>
            </li>
        </ul>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="controls">
                    <div style="float: right;margin-right: 20px"><a href="#" id="com_add" class="btn btn-success" >新增工资字段</a></div>
                </div>
            </div>
            <div class="span12"><div class="widget-box">
                    <div class="widget-content tab-content ">
                        <div class="tab-pane active" id="tab1">


                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th><div>字段名称</div></th>
                                    <th><div>字段类别</div></th>
                                    <th><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">

                                <?php foreach ($ziduanList as $row){?>
                                    <tr >
                                        <td><div><?php echo $row['zd_name'];?></div></td>
                                        <td><div><?php echo $row['zd_type'];?></div></td>
                                        <td class="tr">
                                            <a title="删除" data-id="<?php echo $row['id'];?>"  class="rowDelete pointer theme-color">删除</a>
                                            <div class="cb"></div>
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
<script language="javascript" type="text/javascript" src="common/common-js/salZiduanList.js" charset="utf-8"></script>
<div class="modal hide" id="modal-event1">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>字段类别信息新增</h3>
    </div>
    <form action="" id="company_validate" method="post" class="form-horizontal"  novalidate="novalidate">
        <div class="modal-body">
            <div class="designer_win">
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>
                    字段名称：<input type="text" name="ziduanName"id="ziduanName"/>
                </div>
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>
                    字段类别：<select id="ziduanType" name="ziduanType">
                        <option value="1">相加项</option>
                        <option value="2">相减项</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="modal-footer modal_operate">
            <button type="button" id="addZd" class="btn btn-primary">添加</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </form>
    <div class="search_suggest" id="custor_search_suggest">
        <ul class="search_ul">

        </ul>
        <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
    </div>
</div>


