<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php $login_error = $form_data['login_error'];?>
    <?php include("tpl/common/public_meta.php"); ?>
</head>
<body>
    <?php if (empty($login_error))include("tpl/common/public_head.php"); ?>

    <?php if (empty($login_error))include("tpl/common/public_menu.php"); ?>

    <!--主体内容开始-->
    <?php include("tpl/$nextPageFile"); ?>
	<!--主体内容结束-->


    <?php include("tpl/common/public_footer.php"); ?>
</body>
</html>