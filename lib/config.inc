<?php
//DB define
//session
require_once( 'lib/db.inc' );
require_once( 'lib/functions.inc' );
require_once( 'lib/log.class.inc' );
require_once( 'lib/EC.class.inc' );

/* define('DB_HOST','qdm-001.hichina.com');
define('DB_NAME','qdm0010465_db');
define('DB_USER','qdm0010465');
define('DB_PASS','l2x2y0t5o8');*/
define('DB_HOST','localhost');
define('DB_NAME','oa_test');
define('DB_USER','root');
define('DB_PASS','Hello0617');
define('UPLOADPATH','upload/');
define('DUIBIUPLOADPATH','upload/duiBi/');
//define('UPLOADPATH','$(UPLOADPATH)');
//分页一页长度
define('PAGE_SIZE',5);
define('PAGE_SIZE_EMPLOY',10);
define('NOTICE_PAGE_SIZE',10);
//设置管理员类型
define('ADMIN_TYPE_SYS',1);
define('ADMIN_TYPE_MON',2);
/**
 * 操作日志
 */

//
define('OP_LOG_LOGIN','管理员登录');
define('OP_LOG_DELETE_ADMIN','删除管理员');
//操作日志添加管理员
define('OP_LOG_ADD_ADMIN','添加管理员');
define('OP_LOG_ADD_EMPLOY','添加员工');
define('OP_LOG_IMPORT_EMPLOY','导入员工');
define('OP_LOG_UPDATE_EMPLOY','修改员工');
define('OP_LOG_DEL_EMPLOY','删除员工');
define('OP_LOG_DEL_COMPANY','删除公司');
define('OP_LOG_UPLOAD_FILE','上传文件');
/**
 * 工资
 */
define('OP_LOG_SAVE_SALARY','保存工资');
define('OP_LOG_SAVE__NIAN_SALARY','保存年终奖');
define('OP_LOG_SAVE__ER_SALARY','保存二次工资');
define('OP_LOG_DEL_SALARY','删除工资');
define('OP_LOG_DEL_NIAN_SALARY','删除年终奖');
define('OP_LOG_UPDATE_PER_SALARY','修改个人工资');
define('OP_LOG_SEND_SALARY','工资发放');
define('OP_LOG_ADD_BILL_INVOICE','添加发票');
define('OP_LOG_ADD_BILL_ZHI','添加支票');
define('OP_LOG_ADD_BILL_ZHIDAO','添加支票到账');
define('ERROR_LOG_PATH','log/');
//设置年终奖类别
define('SALARY_TIME_TYPE',5);
//设置二次工资类别
define('ER_SALARY_TIME_TYPE',6);

define('UPLOAD_FILE_NAME','upload.');
define('DOWNLOAD_SALARY_PATH','template/salTemlate.xls');
define('DOWNLOAD_FUKUANDAN_PATH','template/fukuandanTemplate.xlsx');

global $userType;
$userType=array(
    0=> "未缴纳保险",
	1 => "本市城镇职工",
	2 => "外埠城镇职工",
	3 => "本市农村劳动力",
    4 => "外地农村劳动力",
    5 => "本市农民工",
	6 => "外地农民工",
);
global $ziduanTupe;
$ziduanTupe=array(
    1=> "相加项",
    2=> "相见项",
);
global $memu;
$memu=array(
    '1' => array(
        'resource'   => '基础信息设置',
        'action' => 'BaseData',
        'icon'		 =>	'edit',
        'son'		 => array(
            '1'	=> array(
                'action' => 'BaseData',
                'mode' => 'toShenfenType',
                'resource'	=> '身份类别',
            ),
            '2'	=> array(
                'action' => 'BaseData',
                'mode' => 'toDepartmentEdit',
                'resource'	=> '部门设置',
            ),
            '3'	=> array(
                'action' => 'BaseData',
                'mode' => 'toSetSalZiduan',
                'resource'	=> '工资字段设置',
            ),
        ),
    ),
    '2' => array(
        'action' => 'Company',
        'resource'   => '企业管理',
        'icon'		 =>	'group',
        'son'		 => array(
            '1'	=> array(
                'action' => 'Company',
                'mode' => 'toCompanyList',
                'resource'	=> '企业信息',
            ),
        ),
    ),
    '3' => array(
        'action' => 'Employ',
        'resource'   => '员工管理',
        'icon'		 =>	'user',
        'son'		 => array(
            '1'	=> array(
                'action' => 'BaseData',
                'mode' => 'toEmployList',
                'resource'	=> '员工查询',
            ),
            '2'	=> array(
                'action' => 'BaseData',
                'mode' => 'toEmImport',
                'resource'	=> '员工导入',
            ),
        ),
    ),
    '4' => array(
        'action' => 'Salary',
        'resource'   => '工资管理',
        'icon'		 =>	'money',
        'son'		 => array(
            '1'	=> array(
                'action' => 'Salary',
                'mode' => 'toSalaryUpload',
                'resource'	=> '做工资',
            ),
            '2'	=> array(
                'action' => 'Salary',
                'mode' => 'toMakeSalary',
                'resource'	=> '自动做工资',
            ),
            '3'	=> array(
                'action' => 'Salary',
                'mode' => 'salarySearchList',
                'resource'	=> '工资查询',
            ),
        ),
    ),
    '5' => array(
        'action' => 'Financial',
        'resource'   => '财务管理',
        'icon'		 =>	'star',
        'son'		 => array(
            '1'	=> array(
                'action' => 'Financial',
                'mode' => 'toFukuanList',
                'resource'	=> '付款通知单',
            ),
            '2'	=> array(
                'action' => 'Financial',
                'mode' => 'toShoukuanList',
                'resource'	=> '收款',
            ),
            '3'	=> array(
                'action' => 'Financial',
                'mode' => 'toFukuandanList',
                'resource'	=> '付款单',
            ),
        ),
    ),

);
global $userTypeName;
$userTypeName=array(
    "未缴纳保险"=>0,
    "实习生"=>0,
	"本市城镇职工"=>1,
	"外埠城镇职工"=>2,
	"本市农村劳动力"=>3,
    "外地农村劳动力"=>4,
    "本市农民工"=>5,
	"外地农民工"=>6,
);
global $billType;
$billType=array(
    '发票'=>'1',
    '支票'=>'2',
    '到账支票'=>'3',
    '工资发放'=>'4',
    '银行到账'=>'3',
);
global $payType;
$payType=array(
    '1'=>'现金',
    '2'=>'刷卡',
    '3'=>'电汇',
);
global $billState;
$billState=array(
    '0'=>'工资已保存',
    '1'=>'发票已开',
    '2'=>'支票已取',
    '3'=>'资金已经到账',
    '4'=>'工资已发放',
);
//发放类型
global $faSalState;
$salState=array(
    '0'=>'未批准发放',
    '1'=>'批准发放',
);
//工资类型
global $salState;
$salState=array(
    '0'=>'工资以保存',
    '1'=>'工资以修改',
);
//员工类型
global $employState;
$employState=array(
    'zhengchang'=>1,
    'lizhi'=>2,
    'hetongdaoqi'=>3,
    '1'=>'正常',
    '2'=>'离职',
    '3'=>'合同到期',
);
//客服工作流程类型
global $serviceState;
$serviceState=array(
    '1'=>'导入员工',
    '2'=>'做工资表',
    '3'=>'添加发票',
    '4'=>'添加支票',
    '5'=>'申请发放',
);
/***
 * <option value="1">国有企业</option>
<option value="2">事业单位</option>
<option value="3">名营企业</option>
<option value="4">外资企业</option>
<option value="5">政府部门</option>
 */
global $companyType;
$companyType=array(
    1=>'国有企业',
    2=>'事业单位',
    3=>'名营企业',
    4=>'外资企业',
    5=>'政府部门',
);
global $companyLevel;
$companyLevel=array(
    1=>'普通客户',
    2=>'中级客户',
    3=>'高级客户',
);
//工资表字段
global $salaryTable;
$salaryTable=array(
    'per_shifaheji' =>'实发合计',
    'per_yingfaheji' =>'个人应发合计',
    'per_shiye' =>'个人失业',
    'per_yiliao' =>'个人医疗',
    'per_yanglao' =>'个人养老',
    'per_gongjijin' =>'个人公积金',
    'per_daikoushui' =>'代扣税',
    'per_koukuangheji' =>'个人扣款合计',
    'com_shiye' =>'单位失业',
    'com_yiliao' =>'单位医疗',
    'com_yanglao' =>'单位养老',
    'com_gongshang' =>'单位工伤',
    'com_shengyu' =>'单位生育',
    'com_gongjijin' =>'单位公积金',
    'com_heji' =>'单位合计',
    'laowufei' =>'劳务费',
    'canbaojin' =>'残保金',
    'danganfei' =>'档案费',
    'paysum_zhongqi' =>'缴中企基业合计',
);

//工资表字段
global $salNianTable;
$salNianTable=array(
    'comName' => '单位',
    'e_name' => '姓名',
    'employid' => '身份证号',
    'nianzhongjiang' => '年终奖',
    'nian_daikoushui' => '年终奖代扣税',
    'yingfaheji' => '当月应发合计',
    'shifajinka' => '实发进卡',
    'jiaozhongqi' => '缴纳中企基业合计',
);

// 个税字段
global $salaryTypeTable;
$salaryTypeTable=array(
    'company_id' =>'个人编号',
    'ename' =>'姓名',
    'e_num' =>'身份证号',
    'salaryTime' =>'个税日期',
    'companyname' =>'所在单位',
    'geshuiSum' =>'个税合计',
);

global $salErTable;
$salErTable=array(
    'dangyueyingfa' =>
        array(
            'key'=>'当月发放工资',
            'width'=>'',
        ),
    'ercigongziheji' =>
        array(
            'key'=>'二次工资合计',
            'width'=>'',
        ),
    'yingfaheji' =>array(
        'key'=>'实际应发合计',
        'width'=>'',
    ),
    'shiye' =>array(
        'key'=>'失业',
        'width'=>'',
    ),
    'yiliao' =>array(
        'key'=>'医疗',
        'width'=>'',
    ),
    'yanglao' =>array(
        'key'=>'养老',
        'width'=>'',
    ),
    'gongjijin' =>array(
        'key'=>'公积金',
        'width'=>'',
    ),
    'yingkoushui' =>array(
        'key'=>'应扣税',
        'width'=>'',
    ),
    'yikoushui' =>array(
        'key'=>'已扣税',
        'width'=>'',
    ),
    'bukoushui' =>array(
        'key'=>'补扣税',
        'width'=>'',
    ),
    'jinka' =>array(
        'key'=>'双薪进卡',
        'width'=>'',
    ),
    'jiaozhongqi' =>array(
        'key'=>'交中企基业合计',
        'width'=>'',
    ),
);

//业务状态字段
global $businessState;
$businessState=array(
    '0'=>'取消办理',
    '1'=>'等待处理',
    '2'=>'正在办理',
    '3'=>'已完成',
    '4'=>'无法办理',
);

global $businessTable;
$businessTable=array(
    'submitTime' => '提交日期',
    'updateTime' => '更新日期',
    'companyName' => '单位名称',
    'employName' => '员工姓名',
    'employId' => '身份证号',
    'employState' => '员工状态',
    'businessName' => '业务名称',
    'tel' => '联系方式',
    'remarks' => '备注',
    'serviceName' => '申请客服',
    'socialSecurityState' => '办理情况',
    'reimbursementTime' => '报销时间',
    'reimbursementValue' => '报销金额',
    'accountTime' => '到账时间',
    'accountValue' => '到账金额',
    'grantTime' => '发放时间',
    'grantValue' => '发放金额',
    'retireTime' => '退休时间',
    'accountComTime' => '返单位时间',
    'accountComValue' => '返单位金额',
    'accountPersonTime' => '返个人时间',
    'accountPersonValue' => '返个人金额',
);

global $businessInfo;
$businessInfo   =   array(
    '1' => '医疗报销',
    '2' => '工伤报销',
    '3' => '失业申报',
    '4' => '生育医疗申报',
    '5' => '生育津贴申报',
    '10' => '退休',
);

global $expensesInfo;
$expensesInfo   =   array(
    '1' => '单位名称',
    '2' => '工资月份',
    '3' => '实发合计',
    '4' => '代扣税',
    '5' => '缴中企合计',
    '6' => '状态',
);
global $accountType;
$accountType   =   array(
    '收入' => 1,
    '支出' => 2,
);
?>
