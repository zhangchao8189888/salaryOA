<?php
$errorMsg=$form_data['error'];
$salarylist=$form_data['salarylist'];
session_start();
$_SESSION['excellsit']=$salarylist;
?>
<script type="text/javascript">
    $(function(){
        $('#test').bind('input propertychange', function() {
            alert("aa");
            $('#content').html($(this).val().length + ' characters');
        });
    });
</script>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
            <a href="index.php?action=Admin&mode=filesUpload">产品导入</a>
            <a href="#" class="current">查看导入文件  </a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>产品导入 </h5>
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