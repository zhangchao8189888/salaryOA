<?php
/**
 * 记录日志
 *
 * @param string $logstring 日志内容
 * @param string $type 日志类型
 */
function g_log($logstring,$type=GALLERY_LOG_TYPE_DEFAULT,$error=0){
	if ($type == GALLERY_LOG_TYPE_AUDIT) {
		$logfile = GALLERY_LOG_FILE_AUDIT;
	}else {
		$logfile = GALLERY_LOG_FILE_DEFAULT;
	}
	$time = date('Y-m-d H:i:s');
	$byuser = g_acl_getUserId();
	$user = 'SYSTEM';
	if (!empty($byuser)) {
		$user = $byuser;
	}
	$logstring = "[$time][$type][$user]".$logstring."\n";
	if ($error == 1) {
		echo $logstring;
	}
	error_log($logstring,3,$logfile);
}

?>
