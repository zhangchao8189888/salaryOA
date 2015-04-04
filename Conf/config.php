<?php
return array( 
'DEBUG_MODE' => false,
'SQL_DEBUG_LOG' => false,
'DB_TYPE'=> 'mysql',
'DB_HOST'=> '192.168.1.139',
'DB_NAME'=>'blackcenter',
'DB_USER'=>'root', 
'DB_PWD'=>'', 
'DB_PORT'=>'3306',
'DB_PREFIX'=>'',
'DB_FIELDS_CACHE'=>false,
'URL_MODEL'=>2,  
'SITE_NAME'=>'黑名单运营中心后台管理系统',
'PAGESIZE'=>15,
'SPLITSIGN'=>';',
'FDC_ADDRESS'=>'http://192.168.1.136/run.php',     

'APP_LOG_SWITCH'=>true,
'RESOURCE_PASS_TIME'=>730,
'COUNT_CONFIG'=>5,  

'PERMISSIONSORT'=>array(
    1=>'黑名单',
    2=>'节点管理',
    3=>'系统管理',
    4=>'站点统计'
),

'PERMISSIONSEC'=>array(
    1=>array(11=>'黑名单',12=>'网站黑名单'),
    2=>array(21=>'节点配置',22=>'同步信息'),
    3=>array(31=>'系统用户',32=>'系统用户组',33=>'系统权限',34=>'其他管理'),
    4=>array(41=>'生产统计')
),
'PROTOCOL'=>array(
	'1'=>'bt',
	'2'=>'http',
	'3'=>'em',
	'4'=>'xl',
),
'STRATEGY'=>array(
	'1'=>'放行',
	'2'=>'阻断',
	'3'=>'重定向',
),

//各命令对应字段配置
'TABLE_FIELD'=>array(
	'black_list'=>array(
		1=>array('infohash','protocol'),
		2=>array('infohash','protocol','strategy','redirecturl','refer','httpurl'),
	),
	'black_list_httpsite'=>array(
		1=>array('protocol','website'),
		2=>array('protocol','strategy','website'),
	),
),
'LIMIT_BLACK'=>100,
);
?>

