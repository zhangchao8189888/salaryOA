<?php
$notice=$form_data['notice'];

?>
<script language="javascript" type="text/javascript">
    $(function(){
        $("#check_notice").click(function(){
            $("#n_content").html("<p>"+$(this).attr("data-content")+"</p>");
            $("#n_title").text($(this).attr("data-title"));
            $('#modal-event1').modal({show:true});
        });

    });
</script>
<style type="text/css">
    sheet__heading{padding:24px 6px}.sheet--padding{padding-top:36px;padding-bottom:36px}.sheet--padding-extra-small{padding-top:12px;padding-bottom:12px}.sheet--padding-small{padding-top:24px;padding-bottom:24px}@media (min-width:768px){.sheet__heading{padding:60px 36px}.sheet--padding{padding-top:48px;padding-bottom:48px}.sheet--padding-extra-small{padding-top:12px;padding-bottom:12px}.sheet--padding-small{padding-top:24px;padding-bottom:24px}.sheet--hero{padding-top:24px!important}}@media (min-width:992px){.sheet__heading{padding:108px 90px}.sheet--padding{padding-top:72px;padding-bottom:72px}.sheet--padding-extra-small{padding-top:12px;padding-bottom:12px}.sheet--padding-small{padding-top:36px;padding-bottom:36px}}
</style>
<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"> <a href="index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>首页</a></div>
    </div>
    <!--End-breadcrumbs-->
    <!--Action boxes-->
    <div class="container-fluid" >
        <div class="quick-actions_homepage">
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
        </div>
        <!--<div class="quick-actions_homepage">
            <ul class="quick-actions">
                <li class="bg_lb"><a href="interface.html"><i class="icon-group"></i>下属信息查询</a></li>
                <li class="bg_ls"><a href="interface.html"><i class="icon-group"></i>人员变动查询</a></li>
            </ul>
        </div>
        <div class="quick-actions_homepage">
            <ul class="quick-actions">
                <li class="bg_lg"><a href="interface.html"><i class="icon-tint"></i>下属绩效查询</a></li>
                <li class="bg_lo"><a href="interface.html"><i class="icon-tint"></i>个人绩效查询</a></li>
                <li class="bg_lb"><a href="interface.html"><i class="icon-tint"></i>绩效申诉</a></li>
            </ul>
        </div>-->
        <div class="row-fluid">
            <div class="span6">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"><i class="icon-ok"></i></span>
                        <h5>系统公告</h5>
                    </div>
                    <div class="widget-content">
                        <div class="todo">
                            <ul>
                                <?php while ($row = mysql_fetch_array($notice)) {?>
                                    <li class="clearfix">
                                        <div class="txt">  <?php  echo $row['title'];?><span class="by label"><?php  echo $row['update_time'];?></span><p><?php echo $row['content']/*wsubstr($row['content'],0,100)*/;?></p></div>
                                        <div class="pull-right"> <a class="tip" href="#"  id="check_notice" data-title="<?php  echo $row['title'];?>" data-content="<?php echo $row['content'];?>" title="查看"><i class="icon-zoom-out"></i></a> </div>
                                    </li>
                                <?php }?>
                                <!--                                <a class="tip" href="#" title="Delete"><i class="icon-remove"></i></a>-->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid" >
            <div class="span12">
                <div class="sheet__heading text-center"  >
                    <h1 class="giga text-heading-alt text-primary xs-margin-null xs-margin-bottom-small sm-margin-bottom-small md-margin-bottom-small">人事通平台</h1>
                    <div class="content--large xs-margin-bottom-large md-margin-bottom-large">
                        <p class="weight--normal xs-margin-null text-primary">
                            让我们一起共创未来！
                        </p></div>
                    <!--<a href="#" class="btn btn-warning">开始您的工作</a>-->
                </div>
                <div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end-main-container-part-->
<div class="modal hide" id="modal-event1">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>公告详情</h3>
    </div>
    <div class="modal-body">
        <div class="designer_win">
            <div class="tips" >标题：<p id="n_title"></p></div>
            <div class="tips" id="n_content"></div>
        </div>
    </div>
    <div class="modal-footer modal_operate">
        <a href="#" class="btn" data-dismiss="modal">关闭</a>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        // === jQeury Gritter, a growl-like notifications === //
        $.gritter.add({
            title:  '合同到期提醒',
            text: '有<em style="color: darkgreen">1</em>个合同到期提醒<br><br><a href="index.php?action=Admin&mode=toNoticePage">查看</a>',
            image:  'common/img/demo/envelope.png',
            sticky: true
        });
    });
</script>