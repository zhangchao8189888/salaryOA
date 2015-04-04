<?php
$errorMsg=$form_data['error'];
$salarylist=$form_data['salarylist'];
session_start();
$_SESSION['excellsit']=$salarylist;
?>
<style type="text/css">
    .ui-slide-gray-a {
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        color: #555;
        background-color: #808080;
        display: inline-block;
    }
    .step {
        height: 40px;
    }

    .ui-slide-gray-a.current {
        color: #FFF;
        background-position: -108px -79px;
    }
    .step .step-item em {
        margin-right: 10px;
        font-style: normal;
    }

    .step.step1 .step-item-1 {
        background-color: #f3f5f6;
        background-position: right 0;
    }

    .step.step1 .step-item-2 {
        background-color: #e0f0f5;
        background-position: right -40px;

    }
    .step.step1 .step-item-3 {
        background: #f3f5f6;
    }
    .ui-slide-gray-a.current {
        color: #FFF;
        background-position: -108px -79px;
    }

    .step .step-item {
        float: left;
        width: 33.3%;
        text-align: center;
        font-size: 16px;
        color: #666;
        line-height: 40px;
        height: 40px;
        background: #f3f5f6 url(common/img/step.png) no-repeat;
    }
    .step .step-item em {
        margin-right: 10px;
        font-style: normal;
    }
    .ui-slide-gray-a.current {
        color: #FFF;
        background-position: -108px -79px;
    }
    .ui-slide-gray-a {
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        color: #555;
        background: url(common/img/step.png) -148px -79px no-repeat;
        display: inline-block;
    }
</style>
<script type="text/javascript">
    $(function(){
        $('#test').bind('input propertychange', function() {
            alert("aa");
            $('#content').html($(this).val().length + ' characters');
        });
    });
    function chanpinDownLoad(){
        $("#iform").attr("action","index.php?action=Admin&mode=fileProDownload");
        //$("#nfname").val($("#newfname").val());
        $("#iform").submit();
    }
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Product&mode=productUpload">产品导入</a>
            <a href="#" class="current">查看导入文件  </a>
        </div>
    </div>
    <div class="step step1">
        <div class="step-item step-item-1"><em class="ui-slide-gray-a ">一</em>导入文件</div>
        <div class="step-item step-item-2"><em class="ui-slide-gray-a current">二</em>导入预览</div>
        <div class="step-item step-item-3"><em class="ui-slide-gray-a">三</em>导入完成</div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>产品导入 </h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form class="form-horizontal" method="post" action="index.php?action=Product&mode=productImport" enctype="multipart/form-data" name="basic_validate" id="basic_validate" novalidate="novalidate">
                            <div class="control-group" id="createError" style="display:none;">
                                <label class="control-label">&nbsp;</label>
                                <div class="controls">
                                    <span class="colorRem"></span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <!--<input type="submit" value="导入" class="btn btn-success" id="submitBtn1" >-->
                                <!--<input type="button" value="导入客户信息列表" class="btn btn-success" onclick="b()"/>-->
                                <input type="submit" value="导入产品信息列表" class="btn btn-success"/>
                                <font color="red"><?php if($errorMsg)echo $errorMsg?></font>
                                <font color="green"><?php if($succ)echo $succ?></font>
                            </div>
                        </form>
                    </div>
                    <table class="table table-bordered table-striped">
                        <?php
                        echo '<tr onmouseover="" onmouseout="">';
                        for ($j=0;$j<count($salarylist['moban'][0]);$j++)
                        {
                            //if($salarylist[Sheet1][$i][$j]!=""){
                            echo '<td><div><font color="green">'.($j+1).'</font></div></td>';
                            //}
                        }
                        echo "</tr>";
                        for ($i=0;$i<count($salarylist['moban']);$i++)
                        {
                            echo '<tr onmouseover="" onmouseout="">';
                            for ($j=0;$j<count($salarylist['moban'][$i]);$j++)
                            {
                                //if($salarylist[Sheet1][$i][$j]!=""){
                                echo '<td><div>'.$salarylist['moban'][$i][$j].'</div></td>';
                                //}
                            }
                            echo "</tr>";
                        }
                        ?>
                    </table>
                    <?php if($errorList[0]['error']!=""){?>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th><div>错误信息</div></th>
                        </tr>
                        <?php foreach ($errorList as $row){?>
                            <tr >

                                <td><div><?php echo $row['error'];?></div></td>

                            </tr>
                        <?php }?>
                        </table>
                        <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>