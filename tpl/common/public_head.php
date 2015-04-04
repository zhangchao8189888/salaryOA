<!--Header-part-->
<?php
session_start ();
$user = $_SESSION ['admin'];
?>
<div id="header">
  <!--<img id="logo" width='160px' src="common/img/pinge_logo.png" />-->
</div>
<!--close-Header-part--> 
<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li class=""><a><i class="icon icon-user"></i><span class="text">&nbsp;<?php echo $user['real_name']; ?>，你好</span></a></li>
    <li class=""><a href=""><i class="icon icon-time"></i>&nbsp;<span class="text" id="Timer"></span></a>
    </li>
  </ul>
</div>
<!--close-top-Header-menu-->
<!--start-top-serch-->
<div id="search" class="navbar navbar-inverse">
	<ul class="nav">
     <a title="" href="index.php?action=Admin&mode=logoff" onClick="return confirm('确定注销退出吗？');"><i class="icon icon-share-alt"></i> <span class="text">[退出系统]</span></a>
    </ul>
</div>
<!--close-top-serch-->
<script type="text/javascript">
//$('#logo').addClass('animated fadeInRight ');
</script>


