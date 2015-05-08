<!--sidebar-menu-->
<?php
    session_start ();
    $user = $_SESSION ['admin'];

?>
<?php if ($user['user_type'] == 1 || $user['user_type'] == 3) {?>
    <div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> 桌面</a>
        <ul>
            <li <?php if($this->mode == 'toIndex'){ ?>class="active"<?php } ?>><a href="index.php"><i class="icon icon-home"></i> <span>首&nbsp;页</span></a> </li>
            <?php
            global $memu1;
            global $memu2;
            if ($user['user_type'] == 1) {$memu_admin = $memu1;}
            if ($user['user_type'] == 3) {$memu_admin = $memu2;}

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
    </div>
<?php } elseif ($user['user_type'] == 2) {?>
    <div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
        <ul>
            <li class=""> <a href="index.php"><i class="icon icon-file"></i> <span>首页</span></a>
            <li class=""> <a href="index.php?action=Employ&mode=toModifyPass"><i class="icon icon-file"></i> <span>修改密码</span></a>
            <li class=""> <a href="index.php?action=Employ&mode=toSalaryList"><i class="icon icon-file"></i> <span>个人工资</span></a>
        </ul>
    </div>
<?php }?>

<!--sidebar-menu-->

<script>
    
</script>