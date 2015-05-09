<?php
require_once ("module/form/SaveSalaryForm.class.php");
require_once ("module/dao/SalaryDao.class.php");
require_once ("module/dao/EmployDao.class.php");
require_once("tools/fileTools.php");
require_once("tools/excel_class.php");
require_once("tools/sumSalary.class.php");
require_once("tools/Classes/PHPExcel.php");
class SaveSalaryAction extends BaseAction {
	/*
	 * @param $actionPath @return TestAction
	 */
	function SaveSalaryAction($actionPath) {
		parent::BaseAction ();
		$this->objForm = new SaveSalaryForm ();
		$this->actionPath = $actionPath;
	}
	function dispatcher() {
		// (1) mode set
		$this->setMode ();
		// (2) COM initialize
		$this->initBase ( $this->actionPath );
		// (3)验证SESSION是否过期
		// $this->checkSession();
		// (4) controll -> Model
		try {
			$this->controller ();
		} catch ( Exception $exp ) {
			$LOG = new log ();
			$date = date ( 'Y-m-d H:i:s' );
			
			$LOG->setLogdata ( 'error_code', $exp->getCode () );
			$LOG->setLogdata ( 'error_msg', $exp->getMessage () );
			
			// $LOG->write('error_code');
			$LOG->write ( 'error_msg' );
			// exit;
		}
		// (5) view
		$this->view ();
		// (6) closeConnect
		$this->closeDB ();
	}
	function setMode() {
		// 模式设定
		$this->mode = $_REQUEST ['mode'];
	}
	function controller() {
		// Controller -> Model
		switch ($this->mode) {
			case "saveSalary" :
				$this->saveSalary ();
				break;
			case "saveNewAddSalary" :
				$this->saveNewAddSalary ();
				break;
			case "saveNianSalary" :
				$this->saveNianSalary ();
				break;
			case "saveErSalary" :
				$this->saveErSalary ();
				break;
			case "searchSalaryTime" :
				$this->searchSalaryTime ();
				break;
			case "searchNianSalaryTime" :
				$this->searchNianSalaryTime ();
				break;
			case "searchErSalaryTime" :
				$this->searchErSalaryTime ();
				break;
			case "searchSalaryByOther" :
				$this->searchSalaryByOther ();
				break;
			case "searchSalaryById" :
				$this->searchSalaryById ();
				break;
			case "searchSalaryByIdJosn" :
				$this->searchSalaryByIdJosn ();
				break;
			case "searchNianSalaryById" :
				$this->searchNianSalaryById ();
				break;
			case "searchNianSalaryByIdJson" :
				$this->searchNianSalaryByIdJson ();
				break;
			case "searchErSalaryById" :
				$this->searchErSalaryById ();
				break;
			case "searchErSalaryTimeListByIdJson" :
				$this->searchErSalaryTimeListByIdJson ();
				break;
			case "searchErSalaryByIdJson" :
				$this->searchErSalaryByIdJson ();
				break;
			case "delSalayByTimeId" :
				$this->delSalayByTimeId ();
				break;
			case "delNianSalayByTimeId" :
				$this->delNianSalayByTimeId ();
				break;
			case "delErSalayByTimeId" :
				$this->delErSalayByTimeId ();
				break;
			case "searchSaveSalaryTime" :
				$this->searchSaveSalaryTime ();
				break;
			case "searchGeshuiByIdJosn" :
				$this->searchGeshuiByIdJosn ();
				break;
			case "searchGeshuiTypeByIdJosn" :
				$this->searchGeshuiTypeByIdJosn ();
				break;
            case "setTypeGeshui" :
                $this->setTypeGeshui ();
                break;
            case "setTypeGongsijibie" :
                $this->setTypeGongsijibie ();
                break;
            case "insGeshuijilu" :
				$this->insGeshuijilu ();
				break;
            case "setTypeCanjiren" :
				$this->setTypeCanjiren ();
				break;
            case "addZengyuan" :
                $this->addZengyuan ();
                break;
			default :
				$this->modelInput ();
				break;
		}
	}
    function AssignTabMonth($date,$step){
        $date= date("Y-m-d",strtotime($step." months",strtotime($date)));//得到处理后的日期（得到前后月份的日期）
        $u_date = strtotime($date);
        $days=date("t",$u_date);// 得到结果月份的天数

        //月份第一天的日期
        $first_date=date("Y-m",$u_date).'-01';
        for($i=0;$i<$days;$i++){
            $for_day=date("Y-m-d",strtotime($first_date)+($i*3600*24));
        }
        $time = array ();
        $time["first"]  =    $first_date;
        $time["last"]   =      $for_day;
        return $time;
    }
    function saveNewAddSalary () {
        $excelMove = $_POST ['excelMove'];
        $excelHead = $_POST ['excelHead'];
        foreach ( $excelHead  as $num => $row ) {
            if (ereg ( $row, "身份证号" )) {
                $sit_shenfenzhenghao = $num; // 等到“身份证”字段的标志位
            } elseif (ereg ( $row, "个人应发合计" )) {
                $sit_gerenyinfaheji = $num; // 得到个人应发合计字段的标志位
            }
        }
        $salTimeId = $_REQUEST['salTimeId'];
        $this->objDao = new SalaryDao ();
        $salTimePo = $this->objDao->getSalaryTimeBySalId($salTimeId);
        if (empty($salTimePo)) {
            $data['code'] = 100001;
            $data['message'] = '未查询到该工资';
            echo json_encode($data);
            exit;
        }
        $salaryList = $_POST['data'];
        // 开始事务
        $this->objDao->beginTransaction ();
        for($i = 0; $i < count ($salaryList); $i ++) {
            // 如果是等于$sit_gerenyinfaheji标志位存储到固定工资表字段中
            $salayList = array ();
            $salayList ['per_yingfaheji'] = $salaryList [$i] [$sit_gerenyinfaheji];
            $salayList ['per_shiye'] = $salaryList [$i] [($sit_gerenyinfaheji + 1)];
            $salayList ['per_yiliao'] = $salaryList [$i] [($sit_gerenyinfaheji + 2)];
            $salayList ['per_yanglao'] = $salaryList [$i] [($sit_gerenyinfaheji + 3)];
            $salayList ['per_gongjijin'] = $salaryList [$i] [($sit_gerenyinfaheji + 4)];
            $salayList ['per_daikoushui'] = $salaryList [$i] [($sit_gerenyinfaheji + 5)];
            $salayList ['per_koukuangheji'] = $salaryList [$i] [($sit_gerenyinfaheji + 6)];
            $salayList ['per_shifaheji'] = $salaryList [$i] [($sit_gerenyinfaheji + 7)];
            $salayList ['com_shiye'] = $salaryList [$i] [($sit_gerenyinfaheji + 8)];
            $salayList ['com_yiliao'] = $salaryList [$i] [($sit_gerenyinfaheji + 9)];
            $salayList ['com_yanglao'] = $salaryList [$i] [($sit_gerenyinfaheji + 10)];
            $salayList ['com_gongshang'] = $salaryList [$i] [($sit_gerenyinfaheji + 11)];
            $salayList ['com_shengyu'] = $salaryList [$i] [($sit_gerenyinfaheji + 12)];
            $salayList ['com_gongjijin'] = $salaryList [$i] [($sit_gerenyinfaheji + 13)];
            $salayList ['com_heji'] = $salaryList [$i] [($sit_gerenyinfaheji + 14)];
            $salayList ['laowufei'] = $salaryList [$i] [($sit_gerenyinfaheji + 15)];
            $salayList ['canbaojin'] = $salaryList [$i] [($sit_gerenyinfaheji + 16)];
            $salayList ['danganfei'] = $salaryList [$i] [($sit_gerenyinfaheji + 17)];
            $salayList ['paysum_zhongqi'] = $salaryList [$i] [($sit_gerenyinfaheji + 18)];

            $salayList ['employid'] = $salaryList [$i] [$sit_shenfenzhenghao];
            $salayList ['salaryTimeId'] = $salTimeId;
            if (!empty($excelMove [$i+1]['add'])){
                $salayList ['sal_add_json'] = json_encode($excelMove [$i+1]['add']);
            }
            if (!empty($excelMove [$i+1]['del'])){
                $salayList ['sal_del_json'] = json_encode($excelMove [$i+1]['del']);
            }
            if (!empty($excelMove [$i+1]['freeTex'])){
                $salayList ['sal_free_json'] = json_encode($excelMove [$i+1]['freeTex']);
            }
            if ($i == ((count ( $salaryList ) - 1))) { // 最后一行为合计所以需要减1
                //查询合计项
                $salSumPo = $this->objDao->searchSumSalaryListBy_SalaryTimeId($salTimeId);
                $salayList ['per_yingfaheji'] += $salSumPo['sum_per_yingfaheji'];
                $salayList ['per_shiye'] += $salSumPo['sum_per_shiye'];
                $salayList ['per_yiliao'] += $salSumPo ['sum_per_yiliao'];
                $salayList ['per_yanglao'] += $salSumPo ['sum_per_yanglao'];
                $salayList ['per_gongjijin'] += $salSumPo ['sum_per_gongjijin'];
                $salayList ['per_daikoushui'] += $salSumPo ['sum_per_daikoushui'];
                $salayList ['per_koukuangheji'] += $salSumPo ['sum_per_koukuangheji'];
                $salayList ['per_shifaheji'] += $salSumPo ['sum_per_shifaheji'];
                $salayList ['com_shiye'] += $salSumPo ['sum_com_shiye'];
                $salayList ['com_yiliao'] += $salSumPo ['sum_com_yiliao'];
                $salayList ['com_yanglao'] += $salSumPo ['sum_com_yanglao'];
                $salayList ['com_gongshang'] += $salSumPo['sum_com_gongshang'];
                $salayList ['com_shengyu'] += $salSumPo['sum_com_shengyu'];
                $salayList ['com_gongjijin'] += $salSumPo['sum_com_gongjijin'];
                $salayList ['com_heji'] += $salSumPo['sum_com_heji'];
                $salayList ['laowufei'] += $salSumPo ['sum_laowufei'];
                $salayList ['canbaojin'] += $salSumPo ['sum_canbaojin'];
                $salayList ['danganfei'] += $salSumPo ['sum_danganfei'];
                $salayList ['paysum_zhongqi'] += $salSumPo ['sum_paysum_zhongqi'];

                // 以上保存成功后，保存合计项
                $lastSumSalaryId = $this->objDao->updateSumSalary ( $salayList );
                if (! $lastSumSalaryId) {
                    $this->objDao->rollback ();
                    $data['mess'] = "保存合计工资失败！";
                    echo json_encode($data);
                    exit;
                }
            } else {
                if (empty($salaryList [$i] [$sit_gerenyinfaheji])) {
                    continue;
                }
                $lastSalaryId = $this->objDao->saveSalary ( $salayList );
            }
            if (! $lastSalaryId && $lastSalaryId != 0) {
                $this->objDao->rollback ();
                $data['mess'] = "保存固定工资失败！";
                echo json_encode($data);
                exit;
            }
        }

        // 事务提交
        $this->objDao->commit ();
        $data['message'] = "保存一次工资成功";
        $data['code'] = "100000";
        echo json_encode($data);
        exit;
    }
    // FIXME 保存工资
	function saveSalary() {
        $excelMove = $_POST ['excelMove'];
        $excelHead = $_POST ['excelHead'];
		$exmsg = new EC (); // 设置错误信息类
		session_start ();
		$company_id = $_POST ['company_id'];
        $comname = $_GET ['salaryDate'];
        $salaryTimeDate = $_POST ['salaryDate']."-01";
        $time   =   $this->AssignTabMonth ($salaryTimeDate,0);
		$shifajian = $_POST ['shifajian'];
		                              // echo $comname.$salaryTime;
		$salaryList = $_POST['data'];
		$mark = $_POST ['mark'];

		foreach ( $excelHead  as $num => $row ) {
			if (ereg ( $row, "身份证号" )) {
				$sit_shenfenzhenghao = $num; // 等到“身份证”字段的标志位
			} elseif (ereg ( $row, "个人应发合计" )) {
				$sit_gerenyinfaheji = $num; // 得到个人应发合计字段的标志位
			}
		}
		$this->objDao = new SalaryDao ();
		// 开始事务
		$this->objDao->beginTransaction ();
		// 查询公司信息
		$company = $this->objDao->getCompanyById($company_id);
		if (! empty ( $company )) {
			
			$companyId = $company ['id'];
			// 根据日期查询公司时间
			$salaryTime = $this->objDao->searchSalTimeByComIdAndSalTime ( $companyId, "{$time["first"]}", "{$time["last"]}", 3 );
			if (! empty ( $salaryTime ['id'] )) {
                $data['mess'] = " $comname 本月已做工资 ,有问题请联系财务！";
                $data['code'] = "100001";
                echo json_encode($data);
                exit;
			}

		} else {
            //公司不存在
		}
		
		// 添加工资日期
		$salaryTime = array ();
		$salaryTime ['companyId'] = $companyId;
		$salaryTime ['salaryTime'] = $salaryTimeDate;
		$salaryTime ['op_salaryTime'] = date ( "Y-m-d H:i:s" );
		$salaryTime ['mark'] = $mark;
        $user = $_SESSION ['admin'];
        if ($user['user_type']== 3) {
            $salaryTime ['department_id'] = $user['user_id'];
        } else {
            $salaryTime ['department_id'] = 0;
        }
		$lastSalaryTimeId = $this->objDao->saveSalaryTime ( $salaryTime );
		if (! $lastSalaryTimeId) {
            $this->objDao->rollback ();
            $data['mess'] = "保存工资时间失败！";
            $data['code'] = "100001";
            echo json_encode($data);
            exit;
		}
		for($i = 0; $i < count ($salaryList); $i ++) {
			// 如果是等于$sit_gerenyinfaheji标志位存储到固定工资表字段中
			$salayList = array ();
			$salayList ['per_yingfaheji'] = $salaryList [$i] [$sit_gerenyinfaheji];
			$salayList ['per_shiye'] = $salaryList [$i] [($sit_gerenyinfaheji + 1)];
			$salayList ['per_yiliao'] = $salaryList [$i] [($sit_gerenyinfaheji + 2)];
			$salayList ['per_yanglao'] = $salaryList [$i] [($sit_gerenyinfaheji + 3)];
			$salayList ['per_gongjijin'] = $salaryList [$i] [($sit_gerenyinfaheji + 4)];
			$salayList ['per_daikoushui'] = $salaryList [$i] [($sit_gerenyinfaheji + 5)];
			$salayList ['per_koukuangheji'] = $salaryList [$i] [($sit_gerenyinfaheji + 6)];
			$salayList ['per_shifaheji'] = $salaryList [$i] [($sit_gerenyinfaheji + 7)];
			$salayList ['com_shiye'] = $salaryList [$i] [($sit_gerenyinfaheji + 8)];
			$salayList ['com_yiliao'] = $salaryList [$i] [($sit_gerenyinfaheji + 9)];
			$salayList ['com_yanglao'] = $salaryList [$i] [($sit_gerenyinfaheji + 10)];
			$salayList ['com_gongshang'] = $salaryList [$i] [($sit_gerenyinfaheji + 11)];
			$salayList ['com_shengyu'] = $salaryList [$i] [($sit_gerenyinfaheji + 12)];
			$salayList ['com_gongjijin'] = $salaryList [$i] [($sit_gerenyinfaheji + 13)];
			$salayList ['com_heji'] = $salaryList [$i] [($sit_gerenyinfaheji + 14)];
			$salayList ['laowufei'] = $salaryList [$i] [($sit_gerenyinfaheji + 15)];
			$salayList ['canbaojin'] = $salaryList [$i] [($sit_gerenyinfaheji + 16)];
			$salayList ['danganfei'] = $salaryList [$i] [($sit_gerenyinfaheji + 17)];
			$salayList ['paysum_zhongqi'] = $salaryList [$i] [($sit_gerenyinfaheji + 18)];

			$salayList ['employid'] = $salaryList [$i] [$sit_shenfenzhenghao];
			$salayList ['salaryTimeId'] = $lastSalaryTimeId;
            if (!empty($excelMove [$i+1]['add'])){
                $salayList ['sal_add_json'] = json_encode($excelMove [$i+1]['add']);
            }
            if (!empty($excelMove [$i+1]['del'])){
                $salayList ['sal_del_json'] = json_encode($excelMove [$i+1]['del']);
            }
            if (!empty($excelMove [$i+1]['freeTex'])){
                $salayList ['sal_free_json'] = json_encode($excelMove [$i+1]['freeTex']);
            }
			if ($i == ((count ( $salaryList ) - 1))) { // 最后一行为合计所以需要减1
			                                  // 以上保存成功后，保存合计项
				$lastSumSalaryId = $this->objDao->saveSumSalary ( $salayList );
				if (! $lastSumSalaryId) {
                    $this->objDao->rollback ();
                    $data['mess'] = "保存合计工资失败！";
                    $data['code'] = "100001！";
                    echo json_encode($data);
                    exit;
				}
			} else {
                if (empty($salaryList [$i] [$sit_gerenyinfaheji])) {
                    continue;
                }
				$lastSalaryId = $this->objDao->saveSalary ( $salayList );
			}
			if (! $lastSalaryId && $lastSalaryId != 0) {
                $this->objDao->rollback ();
                $data['mess'] = "保存固定工资失败！";
                $data['code'] = "100001";
                echo json_encode($data);
                exit;
			}
			
			if (! empty ( $shifajian ) && $i != ((count ( $salaryList ) - 1))) {
				/**
				 * $salaryList[Sheet1][0][($count+20)]="实发合计减后项";
				 * $salaryList[Sheet1][0][($count+21)]="交中企基业减后项";
				 * $salaryList[Sheet1][0][($count+22)]="实发扣减项";
				 * $salaryList[Sheet1][$i][($count+20)]=sprintf("%01.2f", ($jisuan_var[$i]['shifaheji']-$salaryList[Sheet1][$i][$shifajian]))+0;
				 * $salaryList[Sheet1][$i][($count+21)]=sprintf("%01.2f", ($jisuan_var[$i]['jiaozhongqiheji']-$salaryList[Sheet1][$i][$shifajian]))+0;
				 * $salaryList[Sheet1][$i][($count+22)]=$salaryList[Sheet1][$i][$shifajian];
				 *
				 * @var unknown_type
				 */
				$salaryMovement = array ();
				$salaryMovement ['fieldName'] = "实发合计减后项";
				$salaryMovement ['salaryId'] = $lastSalaryId;
				$salaryMovement ['fieldValue'] = $salaryList [$i] [($sit_gerenyinfaheji + 20)];
				$lastSalaryMovementId = $this->objDao->saveSalaryMovement ( $salaryMovement );
				if (! $lastSalaryMovementId && $lastSalaryId != 0) {
					$exmsg->setError ( __FUNCTION__, "save  salaryMovement get last_insert_id  faild " );
					// 事务回滚
					$this->objDao->rollback ();
					$this->objForm->setFormData ( "warn", "保存动态工资字段失败！" );
					throw new Exception ( $exmsg->error () );
				}
				$salaryMovement = array ();
				$salaryMovement ['fieldName'] = "交中企基业减后项";
				$salaryMovement ['salaryId'] = $lastSalaryId;
				$salaryMovement ['fieldValue'] = $salaryList [$i] [($sit_gerenyinfaheji + 21)];
				$lastSalaryMovementId = $this->objDao->saveSalaryMovement ( $salaryMovement );
				if (! $lastSalaryMovementId && $lastSalaryId != 0) {
					$exmsg->setError ( __FUNCTION__, "save  salaryMovement get last_insert_id  faild " );
					// 事务回滚
					$this->objDao->rollback ();
					$this->objForm->setFormData ( "warn", "保存动态工资字段失败！" );
					throw new Exception ( $exmsg->error () );
				}
			}
		}

		// 事务提交
		$this->objDao->commit ();
        $data['mess'] = "保存一次工资成功";
        $data['code'] = "100000";
        echo json_encode($data);
        exit;
	}
	
	//FIXME 保存年终奖
	function saveNianSalary() {
		$exmsg = new EC (); // 设置错误信息类
		$adminPO = $_SESSION ['admin'];
		session_start ();
		$comname = $_GET ['comname'];
		$salaryTimeDate = $_POST ['salaryTime'];
		$salaryList = $_SESSION ['excelList'];
		// var_dump($salaryList);
		foreach ( $salaryList [0] as $num => $row ) {
			if (ereg ( $row, "身份证号" )) { // ereg字符串比对解析。
				$sit_shenfenzhenghao = $num; // 得到“身份证”字段的标志位
			} elseif (ereg ( $row, "年终奖" )) {
				$sit_nianzhongjiang = $num; // 得到年终奖字段的标志位
			}
		}
		$this->objDao = new SalaryDao ();
		// 开始事务
		$this->objDao->beginTransaction ();
		/*
		 * //根据日期查询公司时间 $salaryTime=$this->objDao->searchSalaryTimeBy_Salarydata($salaryTimeDate); if(!empty($salaryTime)){ $this->objForm->setFormData("warn","工资月份日期已经存在！"); $this->searchSalaryTime(); }
		 */
		// 查询公司信息
		$company = $this->objDao->searchCompanyByName ( $comname );
		if (empty ( $company )) {
			// 添加公司信息
			$companyList = array ();
			$companyList ['name'] = $comname;
			$companyId = $this->objDao->addCompany ( $companyList );
			if (! $companyId) {
				
				$exmsg->setError ( __FUNCTION__, "save  company  get last_insert_id  faild " );
				// 事务回滚
				$this->objDao->rollback ();
				$this->objForm->setFormData ( "warn", "保存工资时间失败！" );
				throw new Exception ( $exmsg->error () );
			}
		} else {
			$companyId = $company ['id'];
		}
		
		// 添加工资日期
		$salaryTime = array ();
		// $salaryTime['companyId']},'{$salaryTime['salaryTime']}','{$salaryTime['op_salaryTime']}
		$salaryTime ['companyId'] = $companyId;
		$salaryTime ['salary_time'] = $salaryTimeDate;
		$salaryTime ['op_salaryTime'] = date ( "Y-m-d" );
		$salaryTime ['salaryType'] = SALARY_TIME_TYPE;
		$salaryTime ['op_id'] = $adminPO ['id'];
		$lastSalaryTimeId = $this->objDao->saveSalaryNianTime ( $salaryTime );
		if (! $lastSalaryTimeId && $lastSalaryId != 0) {
			$exmsg->setError ( __FUNCTION__, "save  salaryNianTime  get last_insert_id  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "保存年终奖工资时间失败！" );
			throw new Exception ( $exmsg->error () );
		}
// 		echo  var_dump($salaryList);
		for($i = 1; $i < count ( $salaryList ); $i ++) {
			// 如果是等于$sit_gerenyinfaheji标志位存储到固定工资表字段中
			$salayList = array ();
			$salayList ['nianzhongjiang'] = $salaryList [$i] [$sit_nianzhongjiang];
			$salayList ['yingfaheji'] = $salaryList [$i] [($sit_nianzhongjiang + 1)];
			$salayList ['nian_daikoushui'] = $salaryList [$i] [($sit_nianzhongjiang + 3)];
			$salayList ['shifajinka'] = $salaryList [$i] [($sit_nianzhongjiang + 4)];
			$salayList ['jiaozhongqi'] = $salaryList [$i] [($sit_nianzhongjiang + 5)];
			$salayList ['employid'] = $salaryList [$i] [$sit_shenfenzhenghao];
			$salayList ['salaryTimeId'] = $lastSalaryTimeId;
			if ($i == ((count ( $salaryList ) - 1))) { // 最后一行为合计所以需要减1
			                                  // 以上保存成功后，保存合计项
				$lastSumSalaryId = $this->objDao->saveSumNianSalary ( $salayList );
				if (! $lastSumSalaryId) {
					$exmsg->setError ( __FUNCTION__, "save  sumNianSalary get last_insert_id  faild " );
					// 事务回滚
					$this->objDao->rollback ();
					$this->objForm->setFormData ( "warn", "保存年终奖合计工资失败！" );
					throw new Exception ( $exmsg->error () );
				}
			} else {
				$lastSalaryId = $this->objDao->saveNianSalary ( $salayList );
			}
			if (! $lastSalaryId && $lastSalaryId != 0) {
				$exmsg->setError ( __FUNCTION__, "save  nian_salary get last_insert_id  faild " );
				// 事务回滚
				$this->objDao->rollback ();
				$this->objForm->setFormData ( "warn", "保存年终奖工资失败！" );
				throw new Exception ( $exmsg->error () );
			}
			/*
			 * if($i!=((count($salaryList)-1))){ //如果是小于$sit_gerenyinfaheji的标志位存储到动态字段中 for($j=0;$j<$sit_gerenyinfaheji;$j++){ //{$salaryMovement['fieldName']}',{$salaryMovement['salaryId']},{$salaryMovement['fieldValue'] $salaryMovement=array(); $salaryMovement['fieldName']=$salaryList[0][$j]; $salaryMovement['salaryId']=$lastSalaryId; $salaryMovement['fieldValue']=$salaryList[$i][$j]; $lastSalaryMovementId=$this->objDao->saveSalaryMovement($salaryMovement); if(!$lastSalaryMovementId&&$lastSalaryId!=0){ $exmsg->setError(__FUNCTION__, "save salaryMovement get last_insert_id faild "); //事务回滚 $this->objDao->rollback(); $this->objForm->setFormData("warn","保存动态工资字段失败！"); throw new Exception ($exmsg->error()); } } }
			 */
		}
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = $lastSalaryTimeId;
		$opLog ['Subject'] = OP_LOG_SAVE__NIAN_SALARY;
		$opLog ['memo'] = '';
		// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$rasult = $this->objDao->addOplog ( $opLog );
		if (! $rasult) {
			$exmsg->setError ( __FUNCTION__, "saveniansalary  add oplog  faild " );
			$this->objForm->setFormData ( "warn", "失败" );
			// 事务回滚
			$this->objDao->rollback ();
			throw new Exception ( $exmsg->error () );
		}
		// 事务提交
		$this->objDao->commit ();
		$this->searchNianSalaryTime ();
	}

	// 个税详细BY孙瑞鹏
	function searchGeshuiByIdJosn() {
		$salaryTimeId = $_REQUEST ['timeId'];
		$salaryTime = $_REQUEST ['time'];
        $nian = $_REQUEST ['nian'];
        $salaryTime=str_replace('\"','"',$salaryTime);
        $salaryTimeId=str_replace('\"','"',$salaryTimeId);
        $nian=str_replace('\"','"',$nian);
        $salaryTimeId=json_decode($salaryTimeId);
        $salaryTime=json_decode($salaryTime);
        $nian=json_decode($nian);
        $salaryTimeId = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", $salaryTimeId);
		$this->objDao = new SalaryDao ();
		$i = 0;
        $daisum = 0;
        $busum = 0;
        $zongsum = 0;
        $niansum = 0;
        $josnArray = array ();
       for($h=0;$h<(count($salaryTimeId));$h++){

           $salaryList = $this->objDao->searchGeshuiBy_SalaryTimeId ( $salaryTimeId[$h], $salaryTime[$h],$nian[$h] );
           while ( $row = mysql_fetch_array ( $salaryList ) ) {
               $josnArray ['items'] [$i] ['company_id'] = $row ['company_id'];
               $josnArray ['items'] [$i] ['ename'] = $row ['ename'];
               $josnArray ['items'] [$i] ['e_num'] = $row ['e_num'];
               $josnArray ['items'] [$i] ['salaryTime'] = $row ['salaryTime'];
               $josnArray ['items'] [$i] ['daikou'] = $row ['daikou'];
               $josnArray ['items'] [$i] ['bukou'] = $row ['bukou'];
               $josnArray ['items'] [$i] ['nian'] = $row ['nian'];
               $josnArray ['items'] [$i] ['companyname'] = $row ['companyname'];
               $josnArray ['items'] [$i] ['geshuiSum'] = $row ['geshuiSum'];
               $daisum += $josnArray['items'][$i] ['daikou'];
               $busum +=  $josnArray['items'][$i] ['bukou'];
               $zongsum += $josnArray['items'][$i]['geshuiSum'];
               $niansum +=  $josnArray ['items'] [$i] ['nian'];
			   $i++;
		   }
        }
        $josnArray['items'][$i] ['daikou']= $daisum;
        $josnArray['items'][$i] ['bukou']= $busum;
        $josnArray['items'][$i]['geshuiSum'] =$zongsum;
        $josnArray ['items'] [$i] ['nian']= $niansum;
        //导出
        $hang=0;
        $salaryListExcel=array();
        $salaryListExcel[$hang][0]="个人编号";
        $salaryListExcel[$hang][1]="姓名";
        $salaryListExcel[$hang][2]="身份证号";
        $salaryListExcel[$hang][3]="个税日期";
        $salaryListExcel[$hang][4]="所在单位";
        $salaryListExcel[$hang][5]="代扣税";
        $salaryListExcel[$hang][6]="补扣税";
        $salaryListExcel[$hang][7]="年终奖扣税";
        $salaryListExcel[$hang][8]="个税合计";
        $hang++;

         foreach ($josnArray['items'] as $value) {
             $salaryListExcel[$hang][0]=$value['company_id'];
             $salaryListExcel[$hang][1]=$value['ename'];
             $salaryListExcel[$hang][2]=$value['e_num'];
             $salaryListExcel[$hang][3]=$value['salaryTime'];
             $salaryListExcel[$hang][4]=$value['companyname'];
             $salaryListExcel[$hang][5]=$value['daikou'];
             $salaryListExcel[$hang][6]=$value['bukou'];
             $salaryListExcel[$hang][7]=$value['nian'];
             $salaryListExcel[$hang][8]=$value['geshuiSum'];
        $hang++;

         }
       // var_dump($salaryListExcel);
        session_start();
        $_SESSION['excelListGeshui']=$salaryListExcel;
		echo json_encode ( $josnArray );
		exit ();
	}
	
	// 个税类型修改BY孙瑞鹏
	function searchGeshuiTypeByIdJosn() {
		// $this->mode="salaryList";
		$salaryTimeId = $_REQUEST ['timeId'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchGeshuiBy_SalaryTypeId ( $salaryTimeId );
		$salaryListArray = array ();
		$salaryListArray ['data'] [] = mysql_fetch_array ( $salaryList );
		echo json_encode ( $salaryListArray );
		exit ();
	}

	// 个税类型修改BY孙瑞鹏
	function setTypeGeshui() {
		// $this->mode="salaryList";
		$salaryTimeId = $_REQUEST ['timeId'];
        $type = $_REQUEST ['type'];
		$this->objDao = new SalaryDao ();
		$this->objDao->setTypeGeshui ( $salaryTimeId,$type );
		// echo json_encode($salaryListArray);
		exit ();
	}
    // 公司级别修改BY孙瑞鹏
    function setTypeGongsijibie() {
        // $this->mode="salaryList";
        $ids=$_POST['ids'];
        $ids=str_replace('\"','"',$ids);
        $ids=json_decode($ids);
        $superId = $_REQUEST ['superId'];
        $this->objDao = new SalaryDao ();
        for($i=0;$i<(count($ids));$i++){
            if($ids[$i]==$superId){
                echo("不可设置子公司为自身！");
                exit ();
           }
            $yanzheng = $this->objDao->selectGongsijibie ($ids[$i]);
            if (mysql_fetch_array($yanzheng)) {
                echo("不可以为有下属公司的公司设置父公司！");
                exit ();
            }
            $result = $this->objDao->setTypeGongsijibie ($ids[$i],$superId);
            if (!$result) {
                echo("操作失败！");
                exit ();
            }
        }
        echo("操作成功！");
        // echo json_encode($salaryListArray);
        exit ();
    }

    // 残疾人修改BY孙瑞鹏
    function setTypeCanjiren() {
        // $this->mode="salaryList";
        $eid = $_REQUEST ['timeId'];
        $type = $_REQUEST ['type'];
        $this->objDao = new SalaryDao ();
        $this->objDao->setTypeCanjiren ( $eid,$type );
        // echo json_encode($salaryListArray);
        exit ();
    }

    // 个税已报标识BY孙瑞鹏
    function insGeshuijilu() {
        $time = date('Y',strtotime("-1 year"));
        $salaryTimeId = $_REQUEST ['timeId'];
        $salaryTime = $_REQUEST ['time'];
        $nian = $_REQUEST ['nian'];
        $salaryTime=str_replace('\"','"',$salaryTime);
        $salaryTimeId=str_replace('\"','"',$salaryTimeId);
        $nian=str_replace('\"','"',$nian);
        $salaryTimeId=json_decode($salaryTimeId);
        $salaryTime=json_decode($salaryTime);
        $nian=json_decode($nian);
        $this->objDao = new SalaryDao ();
        for($h=0;$h<(count($salaryTimeId));$h++){
            $type1=$this->objDao->isGeshui ($salaryTimeId[$h], $salaryTime[$h],1);
            $type2=$this->objDao->isYueGeshui ($salaryTimeId[$h], $salaryTime[$h]);
            if(mysql_fetch_array ( $type1 ) == null && mysql_fetch_array ( $type2 ) != null){
             $this->objDao->insertGeshui ($salaryTimeId[$h], $salaryTime[$h],1);
             if($nian[$h] == 1 ){
                 $typenian1=$this->objDao->isGeshui ($salaryTimeId[$h], $time,2);
                 $typenian2=$this->objDao->isNianGeshui ($salaryTimeId[$h], $time);
                 if(mysql_fetch_array ( $typenian1 ) == null && mysql_fetch_array ( $typenian2 ) != null){
                 $this->objDao->insertNianGeshui ($salaryTimeId[$h], $time,2);
                 }
             }
        }

    }
        exit ();
    }

    // 增员BY孙瑞鹏
    function addZengyuan() {
        $yongren = $_REQUEST ['yongren'];
        $waiqu = $_REQUEST ['waiqu'];
        $shebao = $_REQUEST ['shebao'];
        $caozuo = $_REQUEST ['caozuo'];
        $leibie = $_REQUEST ['leibie'];
        $type = $_REQUEST ['add_type'];
        $companyName = $_REQUEST ['companyName'];
        $employName = $_REQUEST ['employName'];
        $employNumber = $_REQUEST ['employNumber'];
        $beizhu = $_REQUEST ['beizhu'];
        $tel = $_REQUEST ['tel'];
        $gongjijin = $_REQUEST ['gongjijin'];
        $shenbao =  '等待办理';
        $kefuName= $_SESSION ['admin'] ['name'];
        $this->jisuan = new sumSalary();
        $gongjijinsum= $this->jisuan->getSumGongjijin($leibie,$gongjijin);
        $shebaosum= $this->jisuan->getSumShebao($leibie,$shebao);
        $this->objDao = new SalaryDao ();
        $this->objDao->setZengyuan ($type,$kefuName,$companyName,$employName,$employNumber,$leibie,$shebao,$waiqu,$shebaosum,$yongren,null,$shenbao,$beizhu,$caozuo,$tel,$gongjijin,$gongjijinsum);
        if($caozuo=='减员'){
            $this->objDao->setTypeLizhi($employNumber);
        }

        exit ();
    }
	
	function saveErSalary() {
		$exmsg = new EC (); // 设置错误信息类
		$adminPO = $_SESSION ['admin'];
		session_start ();
		$comname = $_GET ['comname'];
		
		$salaryTimeDate = $_POST ['salaryTime'];
		echo $comname . $salaryTimeDate;
		$salaryList = $_SESSION ['excelList'];
		// var_dump($salaryList);
		foreach ( $salaryList [0] as $num => $row ) {
			if (ereg ( $row, "身份证号" )) {
				$sit_shenfenzhenghao = $num; // 等到“身份证”字段的标志位
			} elseif (ereg ( $row, "二次工资合计" )) {
				$sit_ercigongziheji = $num; // 得到年终奖字段的标志位
			}
		}
		$this->objDao = new SalaryDao ();
		// 开始事务
		$this->objDao->beginTransaction ();
		// 查询公司信息
		$company = $this->objDao->searchCompanyByName ( $comname );
		if (empty ( $company )) {
			// 添加公司信息
			$companyList = array ();
			$companyList ['name'] = $comname;
			$companyId = $this->objDao->addCompany ( $companyList );
			if (! $companyId) {
				$exmsg->setError ( __FUNCTION__, "save  company  get last_insert_id  faild " );
				// 事务回滚
				$this->objDao->rollback ();
				$this->objForm->setFormData ( "warn", "保存二次工资时间失败！" );
				throw new Exception ( $exmsg->error () );
			}
		} else {
			$companyId = $company ['id'];
		}
		
		// 添加工资日期
		$salaryTime = array ();
		// $salaryTime['companyId']},'{$salaryTime['salaryTime']}','{$salaryTime['op_salaryTime']}
		$salaryTime ['companyId'] = $companyId;
		$salaryTime ['salary_time'] = $salaryTimeDate;
		$salaryTime ['op_salaryTime'] = date ( "Y-m-d" );
		$salaryTime ['salaryType'] = ER_SALARY_TIME_TYPE;
		$salaryTime ['op_id'] = $adminPO ['id'];
		$lastSalaryTimeId = $this->objDao->saveSalaryNianTime ( $salaryTime );
		if (! $lastSalaryTimeId && $lastSalaryId != 0) {
			$exmsg->setError ( __FUNCTION__, "save  salaryNianTime  get last_insert_id  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "保存二次工资时间失败！" );
			throw new Exception ( $exmsg->error () );
		}
		for($i = 1; $i < count ( $salaryList ); $i ++) {
			// 如果是等于$sit_gerenyinfaheji标志位存储到固定工资表字段中
			$salayList = array ();
			$salayList ['ercigongziheji'] = $salaryList [$i] [$sit_ercigongziheji];
			$salayList ['dangyueyingfa'] = $salaryList [$i] [($sit_ercigongziheji + 1)];
			$salayList ['yingfaheji'] = $salaryList [$i] [($sit_ercigongziheji + 2)];
			$salayList ['shiye'] = $salaryList [$i] [($sit_ercigongziheji + 3)];
			$salayList ['yiliao'] = $salaryList [$i] [($sit_ercigongziheji + 4)];
			$salayList ['yanglao'] = $salaryList [$i] [($sit_ercigongziheji + 5)];
			$salayList ['gongjijin'] = $salaryList [$i] [($sit_ercigongziheji + 6)];
			$salayList ['yingkoushui'] = $salaryList [$i] [($sit_ercigongziheji + 7)];
			$salayList ['yikoushui'] = $salaryList [$i] [($sit_ercigongziheji + 8)];
			$salayList ['bukoushui'] = $salaryList [$i] [($sit_ercigongziheji + 9)];
			$salayList ['jinka'] = $salaryList [$i] [($sit_ercigongziheji + 10)];
			$salayList ['jiaozhongqi'] = $salaryList [$i] [($sit_ercigongziheji + 11)];
			$salayList ['employid'] = $salaryList [$i] [$sit_shenfenzhenghao];
			$salayList ['salaryTimeId'] = $lastSalaryTimeId;
			if ($i == ((count ( $salaryList ) - 1))) { // 最后一行为合计所以需要减1
			                                  // 以上保存成功后，保存合计项
				$lastSumSalaryId = $this->objDao->saveSumErSalary ( $salayList );
				if (! $lastSumSalaryId) {
					$exmsg->setError ( __FUNCTION__, "save  sumNianSalary get last_insert_id  faild " );
					// 事务回滚
					$this->objDao->rollback ();
					$this->objForm->setFormData ( "warn", "保存二次工资合计失败！" );
					throw new Exception ( $exmsg->error () );
				}
			} else {
				$lastSalaryId = $this->objDao->saveErSalary ( $salayList );
			}
			if (! $lastSalaryId && $lastSalaryId != 0) {
				$exmsg->setError ( __FUNCTION__, "save  nian_salary get last_insert_id  faild " );
				// 事务回滚
				$this->objDao->rollback ();
				$this->objForm->setFormData ( "warn", "保存二次工资失败！" );
				throw new Exception ( $exmsg->error () );
			}
			if ($i != ((count ( $salaryList ) - 1))) {
				// 如果是小于$sit_gerenyinfaheji的标志位存储到动态字段中
				for($j = 0; $j < $sit_ercigongziheji; $j ++) {
					// {$salaryMovement['fieldName']}',{$salaryMovement['salaryId']},{$salaryMovement['fieldValue']
					$salaryMovement = array ();
					$salaryMovement ['fieldName'] = $salaryList [0] [$j];
					$salaryMovement ['ersalaryId'] = $lastSalaryId;
					$salaryMovement ['fieldValue'] = $salaryList [$i] [$j];
					$lastSalaryMovementId = $this->objDao->saveErSalaryMovement ( $salaryMovement );
					if (! $lastSalaryMovementId && $lastSalaryId != 0) {
						$exmsg->setError ( __FUNCTION__, "save  salaryMovement get last_insert_id  faild " );
						// 事务回滚
						$this->objDao->rollback ();
						$this->objForm->setFormData ( "warn", "保存动态工资字段失败！" );
						throw new Exception ( $exmsg->error () );
					}
				}
			}
		}
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = $lastSalaryTimeId;
		$opLog ['Subject'] = OP_LOG_SAVE__ER_SALARY;
		$opLog ['memo'] = '';
		// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$rasult = $this->objDao->addOplog ( $opLog );
		if (! $rasult) {
			$exmsg->setError ( __FUNCTION__, "saveniansalary  add oplog  faild " );
			$this->objForm->setFormData ( "warn", "失败" );
			// 事务回滚
			$this->objDao->rollback ();
			throw new Exception ( $exmsg->error () );
		}
		// 事务提交
		$this->objDao->commit ();
		$this->searchErSalaryTime ();
	}
	
	// FIXME 查询工资明细
	function searchSalaryTime() {
		$this->mode = "salaryTimeList";
		$this->objDao = new SalaryDao ();
		$salaryTimeList = $this->objDao->searhSalaryTimeList ();
		$this->objForm->setFormData ( "salaryTimeList", $salaryTimeList );
	}
	function searchNianSalaryTime() {
		$this->mode = "salaryNianTimeList";
		$this->objDao = new SalaryDao ();
		$salaryTimeList = $this->objDao->searhSalaryNianTimeList ();
		$this->objForm->setFormData ( "salaryTimeList", $salaryTimeList );
	}
	function searchErSalaryTime() {
		$this->mode = "salaryErTimeList";
		$this->objDao = new SalaryDao ();
		$salaryTimeList = $this->objDao->searhSalaryErTimeList ();
		$this->objForm->setFormData ( "salaryTimeList", $salaryTimeList );
	}
	function searchSalaryByOther() {
		$type = $_POST ['modeType'];
		if ($type == 'import') {
			$this->mode = "toSalComlist";
		} else if ($type == 'service') {
			$this->mode = "toServiceComlist";
		} else if ($type == 'caiWuImport') {
			$this->mode = "toCaiwuImport";
		} else {
			$this->mode = "salaryTimeList";
		}
		$where = array ();
		$where ['companyName'] = $_POST ['comname'];
		$where ['salaryTime'] = $_POST ['salaryTime'];
		$where ['op_salaryTime'] = $_POST ['opTime'];
		$this->objDao = new SalaryDao ();
		$salaryTimeList = $this->objDao->searhSalaryTimeList ( $where );
		if ($type == 'caiWuImport') {
			$result = $this->objDao->getCompanyLisyByName ( $where ['companyName'] );
			$salErList = array ();
			while ( $row = mysql_fetch_array ( $result ) ) {
				$companyId = $row ['id'];
				$salaryNianList = $this->objDao->searchNianSalaryAndErSalaryTimeByComId ( $companyId );
				$salErList = array ();
				$s = 0;
				while ( $list = mysql_fetch_array ( $salaryNianList ) ) {
					$salErList [$list ['salaryTime']] [$s] = $list;
					$s ++;
				}
				$salErList [$companyId] = $salErList;
				$this->objForm->setFormData ( "salErList", $salErList );
			}
		}
		$this->objForm->setFormData ( "salaryTimeList", $salaryTimeList );
	}
	function searchSalaryById() {
		$this->mode = "salaryList";
		$salaryTimeId = $_REQUEST ['id'];
		$this->objDao = new SalaryDao ();
		$salaryPO = $this->objDao->searchSalaryTimeBy_id ( $salaryTimeId );
        $salaryList = $this->objDao->searchSalaryListBy_SalaryTimeId ( $salaryTimeId );
        $salarySumList = $this->objDao->searchSumSalaryListBy_SalaryTimeId ( $salaryTimeId );
        $salaryListArray = array ();
		$i = 0;
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			$salaryMovementList = $this->objDao->searchSalaryMovementBy_SalaryId ( $row ['id'] );
			while ( $row_move = mysql_fetch_array ( $salaryMovementList ) ) {
				$salaryListArray [$i] [$row_move ['fieldName']] = $row_move ['fieldValue'];
			}
			// var_dump($row) ;
			if (array_keys ( $row ) !== "salary_type") {
				$salaryListArray [$i] ['guding_salary'] = $row;
			}
			if ($row ['salary_type'] == 1) {
				$result = $this->objDao->getOpLog ( null, $row ['id'], " subject='" . OP_LOG_UPDATE_PER_SALARY . "'" );
				$log = "<font style='word-wrap:break-word; background-color:red;'>";
				$row_log = mysql_fetch_array ( $result );
				$log .= "修改时间" . $row_log ['time'] . ':' . $row_log ['memo'];
				$log .= "</font>";
			} else if ($i != 0) {
				$log = " ";
			}
			unset ( $row ['salary_type'] );
			unset ( $row ['22'] );
			$salaryListArray [$i] ['guding_salary'] = $row;
			$salaryListArray [$i] ['log'] = $log;
			$i ++;
		}
		// $salarySumListArray=array();
		$this->objForm->setFormData ( "salaryPO", $salaryPO );
		$this->objForm->setFormData ( "salaryTimeList", $salaryListArray );
		$this->objForm->setFormData ( "salarySumTimeList", $salarySumList );
	}
	function searchSalaryByIdJosn() {
		// $this->mode="salaryList";
		$salaryTimeId = $_REQUEST ['timeId'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salaryListArray = array ();
		$i = 0;
		global $salaryTable;
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			foreach ( $salaryTable as $key => $value ) {
				$rowSalCol = array ();
				$rowFields = array ();
				if ($i == 0) {
					$rowSalCol ['text'] = $value;
					$rowSalCol ["dataIndex"] = $key;
                    if( $key !="姓名"  && $key !="部门"  && $key !="身份证号"  && $key !="身份类别"  &&$key !="银行卡号"    ){
					$rowSalCol ["summaryType"] = 'sum';
                }
                    if( $key =="姓名"  || $key =="部门"  || $key =="身份证号"     ){
                        $rowSalCol ["locked"] = true;
                    }

					// summaryType: 'count',
					if ($key == 'paysum_zhongqi') {
						$rowSalCol ["width"] = 150;
					} else {
						$rowSalCol ["width"] = 80;
					}
					$salaryListArray ['columns'] [] = $rowSalCol;
				}
				$rowFields ["name"] = $key;
                if( $key !="姓名"  && $key !="部门"  && $key !="身份证号"  && $key !="身份类别"  &&$key !="银行卡号"    ){
                    $rowFields ["type"] = 'float';
                }

				// type: 'int'
				$salaryListArray ['fields'] [] = $rowFields;
				$rowData [$key] = $row [$key];
				$rowData [$key] = $row [$key];
				$rowData [$key] = $row [$key];
				$rowData [$key] = $row [$key];
			}
			$salaryListArray ['data'] [] = $rowData;
			$i ++;
		}
		echo json_encode ( $salaryListArray );
		exit ();
	}
	function searchNianSalaryById() {
		$this->mode = "nianSalaryList";
		$salaryTimeId = $_REQUEST ['id'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchNianSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salarySumList = $this->objDao->searchSumNianSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salaryListArray = array ();
		$i = 0;
		$this->objDao = new EmployDao ();
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			$employ = $this->objDao->getEmByEno ( $row ['employid'] );
			$row ['comName'] = $employ ['e_company'];
			$row ['e_name'] = $employ ['e_name'];
			$salaryListArray [$i] = $row;
			// $salaryListArray[$i]['log']=$log;
			$i ++;
		}
		// $salarySumListArray=array();
		$this->objForm->setFormData ( "salaryTimeList", $salaryListArray );
		$this->objForm->setFormData ( "salarySumTimeList", $salarySumList );
	}
	function searchNianSalaryByIdJson() {
		$salaryTimeId = $_REQUEST ['timeId'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchNianSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salaryListArray = array ();
		$i = 0;
		$this->objDao = new EmployDao ();
		global $salNianTable;
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			$employ = $this->objDao->getEmByEno ( $row ['employid'] );
			$row ['comName'] = $employ ['e_company'];
			$row ['e_name'] = $employ ['e_name'];
			foreach ( $salNianTable as $key => $value ) {
				$rowSalCol = array ();
				$rowFields = array ();
				if ($i == 0) {
					$rowSalCol ['text'] = $value;
					if ($value == '单位' || $value == '身份证号' || $value == '姓名') {
						$rowSalCol ["locked"] = true;
					} else {
						$rowSalCol ["summaryType"] = 'sum';
					}
					$rowSalCol ["dataIndex"] = $key;
					
					// summaryType: 'count',
					$salaryListArray ['columns'] [] = $rowSalCol;
				}
				$rowFields ["name"] = $key;
				if ($value != '单位' && $value != '身份证号' && $value != '姓名') {
					$rowFields ["type"] = 'float';
				}
				
				// type: 'int'
				$salaryListArray ['fields'] [] = $rowFields;
				$rowData [$key] = $row [$key];
			}
			$salaryListArray ['data'] [] = $rowData;
			$i ++;
		}
		echo json_encode ( $salaryListArray );
		exit ();
	}
	function searchErSalaryById() {
		$this->mode = "erSalaryList";
		$salaryTimeId = $_REQUEST ['id'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchErSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salarySumList = $this->objDao->searchSumErSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salaryListArray = array ();
		$i = 0;
		$this->objDao = new SalaryDao ();
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			$salaryMovementList = $this->objDao->searchErSalaryMovementBy_SalaryId ( $row ['id'] );
			while ( $row_move = mysql_fetch_array ( $salaryMovementList ) ) {
				// var_dump($row_move);
				$salaryListArray [$i] [$row_move ['fieldName']] = $row_move ['fieldValue'];
			}
			// var_dump($salaryListArray) ;
			if (array_keys ( $row ) !== "salary_type") {
				$salaryListArray [$i] ['guding_salary'] = $row;
			}
			$i ++;
		}
		// var_dump($salaryListArray);
		// $salarySumListArray=array();
		$this->objForm->setFormData ( "salaryTimeList", $salaryListArray );
		$this->objForm->setFormData ( "salarySumTimeList", $salarySumList );
	}
	function searchErSalaryTimeListByIdJson() {
		$companyId = $_REQUEST ['companyId'];
		$salTime = $_REQUEST ['salTime'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchErSalaryTimeBySalaryTimeAndComId ( $salTime, $companyId );
		$salTimeList = array ();
		$i = 0;
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			$salTimeList [$i] ['salTimeId'] = $row ['id'];
			$salTimeList [$i] ['salaryTime'] = $row ['salaryTime'];
			$i ++;
		}
		echo json_encode ( $salTimeList );
		exit ();
	}
	function searchErSalaryByIdJson() {
		$this->mode = "erSalaryList";
		$salaryTimeId = $_REQUEST ['timeId'];
		$this->objDao = new SalaryDao ();
		$salaryList = $this->objDao->searchErSalaryListBy_SalaryTimeId ( $salaryTimeId );
		$salaryListArray = array ();
		$i = 0;
		$this->objDao = new SalaryDao ();
		global $salErTable;
		$movKeyArr = array ();
		$z = 0;
		while ( $row = mysql_fetch_array ( $salaryList ) ) {
			$salaryMovementList = $this->objDao->searchErSalaryMovementBy_SalaryId ( $row ['id'] );
			$j = 0;
			while ( $row_move = mysql_fetch_array ( $salaryMovementList ) ) {
				$rowFields = array ();
				$rowCol = array ();
				if ($row_move ['fieldName'] == NULL) {
					continue;
				}
				if ($i == 0) {
					$rowCol ['text'] = $row_move ['fieldName'];
					if ($row_move ['fieldName'] == '部门' || $row_move ['fieldName'] == '身份证号' || $row_move ['fieldName'] == '姓名') {
						// hidden:true
						$rowCol ["locked"] = true;
					} else {
						$rowCol ["hidden"] = true;
					}
					$rowCol ["dataIndex"] = $row_move ['id'];
					$salaryListArray ['columns'] [] = $rowCol;
					$movKeyArr [$z] = $row_move ['id'];
					$z ++;
				}
				$rowData ["{$movKeyArr[$j]}"] = $row_move ['fieldValue'];
				$j ++;
				if ($i == 0) {
					$rowFields ["name"] = "{$row_move['id']}";
					$salaryListArray ['fields'] [] = $rowFields;
				}
			}
			
			foreach ( $salErTable as $key => $value ) {
				$rowSalCol = array ();
				$rowFields = array ();
				if ($i == 0) {
					$rowSalCol ['text'] = $value ['key'];
					$rowSalCol ["dataIndex"] = $key;
					$rowSalCol ["summaryType"] = 'sum';
					// summaryType: 'count',
					if (! empty ( $value ['width'] )) {
						$rowSalCol ["width"] = $value ['width'];
					}
					$salaryListArray ['columns'] [] = $rowSalCol;
				}
				$rowFields ["name"] = $key;
				$rowFields ["type"] = 'float';
				// type: 'int'
				$salaryListArray ['fields'] [] = $rowFields;
				$rowData [$key] = $row [$key];
			}
			$salaryListArray ['data'] [] = $rowData;
			$i ++;
		}
		echo json_encode ( $salaryListArray );
		exit ();
	}
	function delSalayByTimeId() {
		$salaryTimeId = $_REQUEST ['timeid'];
		$exmsg = new EC (); // 设置错误信息类
		$this->objDao = new SalaryDao ();
		// 开始事务
		$this->objDao->beginTransaction ();
		$salaryList = $this->objDao->searchSalaryTimeBy_id ( $salaryTimeId );
		$result = $this->objDao->delSalaryMovement_BySalaryId ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salaryMovement  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除动态工资字段失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$result = $this->objDao->delSalaryBy_TimeId ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salary  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除固定工资字段失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$result = $this->objDao->delSalaryTimeBy_Id ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salaryTime  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除工资时间表失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$adminPO = $_SESSION ['admin'];
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = 0;
		$opLog ['Subject'] = OP_LOG_DEL_SALARY;
		$opLog ['memo'] = $salaryList ['company_name'] . ':' . $salaryList ['salaryTime'];
		// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$rasult = $this->objDao->addOplog ( $opLog );
		if (! $rasult) {
			$exmsg->setError ( __FUNCTION__, "delsalary  add oplog  faild " );
			$this->objForm->setFormData ( "warn", "失败" );
			// 事务回滚
			$this->objDao->rollback ();
			throw new Exception ( $exmsg->error () );
		}
		// 事务提交
		$this->objDao->commit ();
		$this->searchSalaryTime ();
	}
	function delNianSalayByTimeId() {
		$salaryTimeId = $_REQUEST ['timeid'];
		$exmsg = new EC (); // 设置错误信息类
		$this->objDao = new SalaryDao ();
		// 开始事务
		$this->objDao->beginTransaction ();
		$salaryList = $this->objDao->searchNianSalaryTimeBy_id ( $salaryTimeId );
		/*
		 * //$result=$this->objDao->delSalaryMovement_BySalaryId($salaryTimeId); if(!$result){ $exmsg->setError(__FUNCTION__, "del salaryMovement faild "); //事务回滚 $this->objDao->rollback(); $this->objForm->setFormData("warn","删除动态工资字段失败！"); throw new Exception ($exmsg->error()); }
		 */
		$result = $this->objDao->delNianSalaryBy_TimeId ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salary  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除固定工资字段失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$result = $this->objDao->delNianSalaryTimeBy_Id ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salaryTime  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除工资时间表失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$adminPO = $_SESSION ['admin'];
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = 0;
		$opLog ['Subject'] = OP_LOG_DEL_NIAN_SALARY;
		$opLog ['memo'] = $salaryList ['company_name'] . ':' . $salaryList ['salaryTime'];
		// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$rasult = $this->objDao->addOplog ( $opLog );
		if (! $rasult) {
			$exmsg->setError ( __FUNCTION__, "delsalary  add oplog  faild " );
			$this->objForm->setFormData ( "warn", "失败" );
			// 事务回滚
			$this->objDao->rollback ();
			throw new Exception ( $exmsg->error () );
		}
		// 事务提交
		$this->objDao->commit ();
		$this->searchNianSalaryTime ();
	}
	function delErSalayByTimeId() {
		$salaryTimeId = $_REQUEST ['timeid'];
		$exmsg = new EC (); // 设置错误信息类
		$this->objDao = new SalaryDao ();
		// 开始事务
		$this->objDao->beginTransaction ();
		$salaryList = $this->objDao->searchNianSalaryTimeBy_id ( $salaryTimeId );
		$result = $this->objDao->delErSalaryMovement_BySalaryId ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salaryMovement  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除动态工资字段失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$result = $this->objDao->delErSalaryBy_TimeId ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salary  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除固定工资字段失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$result = $this->objDao->delNianSalaryTimeBy_Id ( $salaryTimeId );
		if (! $result) {
			$exmsg->setError ( __FUNCTION__, "del   salaryTime  faild " );
			// 事务回滚
			$this->objDao->rollback ();
			$this->objForm->setFormData ( "warn", "删除工资时间表失败！" );
			throw new Exception ( $exmsg->error () );
		}
		$adminPO = $_SESSION ['admin'];
		$opLog = array ();
		$opLog ['who'] = $adminPO ['id'];
		$opLog ['what'] = 0;
		$opLog ['Subject'] = OP_LOG_DEL_NIAN_SALARY;
		$opLog ['memo'] = $salaryList ['company_name'] . ':' . $salaryList ['salaryTime'];
		// {$OpLog['who']},{$OpLog['what']},{$OpLog['Subject']},{$OpLog['time']},{$OpLog['memo']}
		$rasult = $this->objDao->addOplog ( $opLog );
		if (! $rasult) {
			$exmsg->setError ( __FUNCTION__, "delsalary  add oplog  faild " );
			$this->objForm->setFormData ( "warn", "失败" );
			// 事务回滚
			$this->objDao->rollback ();
			throw new Exception ( $exmsg->error () );
		}
		// 事务提交
		$this->objDao->commit ();
		$this->searchErSalaryTime ();
	}
}

$objModel = new SaveSalaryAction ( $actionPath );
$objModel->dispatcher ();

?>
