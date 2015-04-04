<?php
$shenfenList=$form_data['shenfenList'];
?>
<script language="javascript" type="text/javascript">
    $(function(){

        $("#com_add").click(function(){
            $('#modal-event1').modal({show:true});
        });
        $(".rowDelete").click(function(){
            var id = $(this).attr("data-id");
            $.ajax(
                {
                    type: "post",
                    url: "index.php?action=BaseData&mode=deleteShenfen",
                    data: {id:id},
                    dataType: "json",
                    success: function(data){
                        if (data.code > 100000) {
                            alert(data.mess);
                            return;
                        }
                        window.location.reload();
                    }
                }
            );
        });
    });
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">基础数据设置</a>
            <a href="#">身份类别</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="controls">
                    <div style="float: right;margin-right: 20px"><a href="#" id="com_add" class="btn btn-success" >新增身份类别</a></div>
                </div>
            </div>
            <div class="span12"><div class="widget-box">
                    <div class="widget-content tab-content ">
                        <div class="tab-pane active" id="tab1">


                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="tl"><div></div></th>
                                    <th class="tl"><div>id</div></th>
                                    <th class="tl"><div>类别名称</div></th>
                                    <th class="tl"><div>类别编号</div></th>
                                    <th class="tl"><div>添加人</div></th>
                                    <th class="tl"><div>添加时间</div></th>
                                    <th class="tl"><div>操作</div></th>
                                </tr>
                                </thead>
                                <tbody  class="tbodays">

                                <?php foreach ($shenfenList as $row){?>
                                    <tr >
                                        <td><div></div></td>
                                        <td><div><?php echo $row['id'];?></div></td>
                                        <td><div><?php echo $row['type_name'];?></div></td>
                                        <td><div><?php echo $row['type_id'];?></div></td>
                                        <td><div><?php echo $row['name'];?></div></td>
                                        <td><div><?php echo $row['create_time'];?></div></td>
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
<script language="javascript" type="text/javascript" src="common/common-js/baseData.js" charset="utf-8"></script>
<div class="modal hide" id="modal-event1">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>身份类别信息新增</h3>
    </div>
    <form action="" id="company_validate" method="post" class="form-horizontal"  novalidate="novalidate">
        <div class="modal-body">
            <div class="designer_win">
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>身份类别：<input type="text" maxlength="20" id="shenfenType"name="shenfenType"  /></div>
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>类别编号：<input type="text" maxlength="20" id="type_id"name="type_id"  /></div>
            </div>
        </div>

        <div class="modal-footer modal_operate">
            <button type="submit" class="btn btn-primary">添加</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </form>
    <div class="search_suggest" id="custor_search_suggest">
        <ul class="search_ul">

        </ul>
        <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
    </div>
</div>


