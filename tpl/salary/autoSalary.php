<?php
$salTime=$form_data['salTime'];
$mess=$form_data['mess'];
$succ=$form_data['succ'];
$ziduanList=$form_data['ziduanList'];
$user = $_SESSION ['admin'];
$companyId = $user['user_id'];
$companyName = $user['real_name'];
?>
<style type="text/css">
    .divCheck{
        word-wrap: break-word;
        word-break: normal;
    }
</style>
<script src="common/hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="common/hot-js/handsontable.full.css">
<script language="javascript" type="text/javascript" src="common/js/jquery.checkbox.js" charset="utf-8"></script>
<script src="common/common-js/autoSalary.js"></script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="#">字段选择</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>字段选择 </h5>
                    </div>
                    <div class="widget-content nopadding">
                            <div class="form-actions">
                                <form id="salForm" action="" method="post">
                                    <div class="tips">
                                        <?php echo '上次做工资月份：'.$salTime['salaryTime'];?>
                                        <em style="color: red;padding-right: 10px;" id="isDianfu"></em>
                                    </div>
                                    <div class="tips"><em style="color: red;padding-right: 10px;">*</em>工资月份：
                                        <input type="text" id="salaryDate" name="salaryDate" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM',realDateFmt:'yyyy-MM'})"/>
                                    </div>
                                    <div class="">
                                        <?php
                                        foreach($ziduanList as $row){
                                            $type = '';
                                            if($row['zd_type'] == 1) {
                                                $type ='+';
                                            } else {
                                                $type ='-';
                                            }
                                            echo '<input name="ziduanId" type="checkbox" value="'.$row['id'].'" checked />'
                                                .$row['zd_name']."（{$row['zd_type_name']}）";
                                        }
                                        ?>
                                    </div>
                                    <input type="button" value="做工资" class="btn btn-success" id="salMake" >
                                </form>
                            </div>
                        </div>

                    </div>
                <div class="widget-box">
                    <div class="tab-content">
                        <div>
                            <div class="controls">
                                <!-- checked="checked"-->
                                <input type="button" value="重置" class="btn btn-primary" id="reload" />
                                <input type="button" value="计算工资" class="btn btn-success" id="sumFirst" />
                            </div>
                            <div id="exampleGrid" class="dataTable" style="width: 500px; height: 500px; overflow: auto"></div>
                        </div>
                    </div>
                </div>
                </div>
            <div class="span12" style="margin-left:0;">
                <div class="widget-box">
                    <div class="tab-content">
                        <div class="tab-pane active" id="home">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="active"><a href="#home">计算结果</a></li>
                                <li><a href="#profile">错误信息<em style="color: red" id="error"></em></a></li>
                            </ul>
                            <div class="controls">
                                <!-- checked="checked"-->
                                <input type="checkbox" id="colHeaders" autocomplete="off"> <span>锁定前两列</span>
                                <input type="button" value="保存工资" class="btn btn-success" id="save" />
                            </div>
                            <div id="sumGrid" class="dataTable" style="width: 1400px; height: 200px; overflow: auto"></div>
                        </div>
                        <div class="tab-pane" id="profile">
                            <table id="errorInfo">

                            </table>
                        </div>
                    </div>

                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="search_suggest" id="custor_search_suggest">
        <ul class="search_ul">

        </ul>
        <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
    </div>
</div>
<div class="modal hide" id="modal-event1">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>保存工资</h3>
    </div>
    <form action="" id="company_validate" method="post" class="form-horizontal"  novalidate="novalidate">
        <div class="modal-body">
            <div class="designer_win">
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>所属公司：
                    <input type="text" maxlength="20" id="e_company"name="e_company" autocomplete="off" value="<?php echo $companyName;?>" readonly />
                    <input type="hidden" id="company_id" name="company_id" value="<?php echo $companyId;?>"/></div>
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>工资月份：
                    <input type="text" id="saveSalDate" name="saveSalDate" value="" readonly/>
                </div>
                <div class="tips">备注：<textarea id="mark">

                    </textarea></div>
            </div>
        </div>

        <div class="modal-footer modal_operate">
            <button type="button" id="salarySave" class="btn btn-primary">保存</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </form>
    <div class="search_suggest" id="custor_search_suggest">
        <ul class="search_ul">

        </ul>
        <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
    </div>
</div>
<script type="text/javascript" src="common/js/datepicker/WdatePicker.js"></script>