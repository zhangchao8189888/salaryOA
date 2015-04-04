<!--sidebar-menu-->
<?php
    session_start ();
    $user = $_SESSION ['admin'];

?>
<?php if ($user['user_type'] == 1 || 1) {?>
    <div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> 桌面</a>
        <ul>
            <li <?php if($this->mode == 'toIndex'){ ?>class="active"<?php } ?>><a href="index.php"><i class="icon icon-home"></i> <span>首&nbsp;页</span></a> </li>
            <?php
            global $memu;
            $memu_admin = $memu;
            if(!empty($memu_admin)){
                foreach ($memu_admin as $a_k => $a_v) {
                    $url = "";
                    //if(in_array($a_k,$this->user_menu_list)){
                    if($a_k){
                        ?>
                        <li class="<?php
                        $controller_class = "";
                        if(isset($a_v['son'])){
                            $controller_class = "submenu";
                            if($a_v['action'] == $this->actionPath){
                                $controller_class .= " open";
                            }
                        }else{
                            if($a_v['mode'] == $this->mode){
                                $controller_class = "active";
                            }
                            $url = "index.php";
                            if (isset($a_v['action'])) {
                                $url.= "?action={$a_v['action']}";
                            }
                            if (isset($a_v['mode'])){
                                $url .= "&mode={$s_v['mode']}";
                            }
                        }
                        echo $controller_class;
                        ?>">
                            <a href="<?php echo $url;?>">
                                <i class="icon icon-<?php echo $a_v['icon']?>"></i>
                                <span><?php echo $a_v['resource']?></span>
                                <?php if(isset($a_v['son'])){?>
                                    <span class="label label-important"><?php echo count($a_v['son'])?></span>
                                <?php }?>
                            </a>
                            <?php if(isset($a_v['son'])){?>
                                <ul>
                                    <?php
                                    $parm_flag = false;
                                    if(in_array($a_v['controller'], array('fragment','tag'))){
                                        $parm_flag = true;
                                    }
                                    foreach ($a_v['son'] as $s_k => $s_v) {
                                        $active_flag = false;

                                        if($a_v['action'].$s_v['mode'] == $this->actionPath.$this->mode){
                                            $active_flag = true;
                                        }
                                        $url = "index.php?action={$a_v['action']}&mode={$s_v['mode']}";
                                        ?>
                                        <li class="<?php echo $active_flag?'active' : ' ' ?>">
                                            <a href="<?php echo $url?>"><?php echo $s_v['resource']?></a>
                                        </li>
                                    <?php }?>
                                </ul>
                            <?php }?>
                        </li>
                    <?php
                    }}}
            ?>
        </ul>
        <!--<ul>
            <li class=""> <a href="index.php"><i class="icon icon-file"></i> <span>首页</span></a>
            <li class="submenu "> <a href="#"><i class="icon icon-file"></i> <span>基础信息设置</span><span class="label label-important">2</span></a>
                <ul>
                    <li class="active"><a href="index.php?action=BaseData&mode=toShenfenType">身份类别</a></li>
                    <li><a href="index.php?action=BaseData&mode=toDepartmentEdit">部门设置</a></li>
                </ul>
            </li>
            <li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>企业管理</span><span class="label label-important">1</span></a>
                <ul>
                    <li><a href="index.php?action=Company&mode=toCompanyList">企业信息</a></li>
                    <li><a href="index.php?action=Company&mode=demoTest">测试</a></li>
                </ul>
            </li>
            <li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>员工管理</span><span class="label label-important">2</span></a>
                <ul>
                    <li><a href="index.php?action=Employ&mode=toEmployList">员工查询</a></li>
                    <li><a href="index.php?action=Employ&mode=toEmImport">员工导入</a></li>
                </ul>
            </li>
            <li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>工资管理</span><span class="label label-important">1</span></a>
                <ul>
                    <li><a href="index.php?action=Salary&mode=toSalaryUpload">做工资</a></li>
                    <li><a href="index.php?action=Salary&mode=salarySearchList">工资查询</a></li>
                </ul>
            </li>
            <li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>财务管理</span><span class="label label-important">1</span></a>
                <ul>
                    <li><a href="index.php?action=Salary&mode=toFukuanList">付款通知单</a></li>
                    <li><a href="index.php?action=Salary&mode=toShoukuanList">收款</a></li>
                    <li><a href="index.php?action=Salary&mode=toFukuandanList">付款单</a></li>
                </ul>
            </li>

            </li>
        </ul>-->
    </div>
<?php } elseif ($user['user_type'] == 2) {?>
    <div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
        <ul>
            <li class=""> <a href="index.php"><i class="icon icon-file"></i> <span>首页</span></a>
            <li class=""> <a href="index.php?action=Employ&mode=toModifyPass"><i class="icon icon-file"></i> <span>修改密码</span></a>
            <li class=""> <a href="index.php?action=Employ&mode=toSalaryList"><i class="icon icon-file"></i> <span>个人工资</span></a>
            <!--<li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>系统设置</span><span class="label label-important">1</span></a>
                <ul>
                    <li><a href="index.php?action=Company&mode=toDepartmentEdit">修改密码</a></li>
                </ul>
            </li>-->

            </li>
            <!--<li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>财务对账</span><span class="label label-important">1</span></a>
                <ul>
                    <li><a href="index.php?action=Duizhang&mode=toAutoDuizhang">自动对账</a></li>
                </ul>
            </li>-->
            <!--<li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>部门设置</span><span class="label label-important">1</span></a>
                <ul>
                    <li><a href="index.php?action=Duizhang&mode=toAutoDuizhang">自动对账</a></li>
                </ul>
            </li>-->
        </ul>
    </div>
<?php }?>

<!--sidebar-menu-->

<script>
    
</script>