<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php $this->renderPartial('//layouts/public_meta'); ?>
</head>
<body>

	<?php $this->renderPartial('//layouts/public_head'); ?>

<div id="main" class="clearfix">

	<!--主体内容开始-->
	<?php echo $content;?>
	<!--主体内容结束-->

	<?php $this->renderPartial('//layouts/public_footer'); ?>

</div>
</body>
</html>