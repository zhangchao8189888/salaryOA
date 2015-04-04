<?php 
class sumSalary{
var $gerenshiye;
var $gerenyiliao;
var $gerenyanglao;
var $gerenheji;
var $danweishiye;
var $danweiyiliao;
var $danweiyanglao;
var $danweigongshang;
var $danweishengyu;
var $danweiheji;	
const  jijinshu=3500;
	function getSumSalary(&$jisuan_var){
		//$shebaojishu,$shenfenleibie
		//var_dump($jisuan_var);
		/**
		 *      1. 应发合计 =基本工资+考核工资												
						
	3. 扣款合计=个人失业+个人医疗+个人养老+个人公积金+代扣税											
	4. 实发合计=应发合计-扣款合计											
	5. 缴中企基业合计=应发合计+单位失业+单位医疗+单位养老+单位工伤+单位生育+单位公积金+劳务费+残保金+档案费	
		 */
		for ($i=1;$i<=count($jisuan_var);$i++){
			$userType=$this->getShenfenleibie($jisuan_var[$i]['shenfenleibie']);
			if($userType!=-1){
		    $jisuan_var[$i]['yingfaheji']=$jisuan_var[$i]["addValue"]-$jisuan_var[$i]["delValue"];
			$jisuan_var[$i]['gerenshiye']=$this->jisuan_geren_shiye($jisuan_var[$i]['shebaojishu'],$userType);
			$jisuan_var[$i]['gerenyiliao']=$this->jisuan_geren_yiliao($jisuan_var[$i]['shebaojishu'],$userType);
			$jisuan_var[$i]['gerenyanglao']=$this->jisuan_geren_yanglao($jisuan_var[$i]['shebaojishu'],$userType);
			$jisuan_var[$i]['gerengongjijin']=round($this->jisuan_geren_gongjijin($jisuan_var[$i]['gongjijinjishu']));
			$jisuan_var[$i]['daikousui']=$this->jisuan_daikousui_xin2011($jisuan_var[$i]);
			$jisuan_var[$i]['koukuanheji']=($jisuan_var[$i]['gerenshiye']+$jisuan_var[$i]['gerenyiliao']+$jisuan_var[$i]['gerenyanglao']+$jisuan_var[$i]['gerengongjijin']+$jisuan_var[$i]['daikousui']);
			$jisuan_var[$i]['shifaheji']=$jisuan_var[$i]['yingfaheji']-$jisuan_var[$i]['koukuanheji'];
			$jisuan_var[$i]['danweishiye']=$this->jisuan_danwei_shiye($jisuan_var[$i]['shebaojishu'],$userType);
			$jisuan_var[$i]['danweigongshang']=$this->jisuan_danwei_gongshang($jisuan_var[$i]['shebaojishu'],$userType);
			$jisuan_var[$i]['danweishengyu']=$this->jisuan_danwei_shengyu($jisuan_var[$i]['shebaojishu'],$userType);
			$jisuan_var[$i]['danweiyanglao']=$this->jisuan_danwei_yanglao($jisuan_var[$i]['shebaojishu'],$userType);
			$jisuan_var[$i]['danweiyiliao']=$this->jisuan_danwei_yiliao($jisuan_var[$i]['shebaojishu'],$userType);
			$jisuan_var[$i]['danweigongjijin']=round($this->jisuan_geren_gongjijin($jisuan_var[$i]['gongjijinjishu']));
			$jisuan_var[$i]['danweiheji']=($jisuan_var[$i]['danweishiye']+$jisuan_var[$i]['danweigongshang']+$jisuan_var[$i]['danweishengyu']+$jisuan_var[$i]['danweiyanglao']+$jisuan_var[$i]['danweiyiliao']+$jisuan_var[$i]['danweigongjijin']);
			$jisuan_var[$i]['jiaozhongqiheji']=$jisuan_var[$i]['yingfaheji']+$jisuan_var[$i]['danweishiye']+$jisuan_var[$i]['danweigongshang']+$jisuan_var[$i]['danweishengyu']+$jisuan_var[$i]['danweiyanglao']+$jisuan_var[$i]['danweigongjijin']+$jisuan_var[$i]['danweiyiliao']+$jisuan_var[$i]['laowufei']+$jisuan_var[$i]['canbaojin']+$jisuan_var[$i]['danganfei'];
			}else{
			$jisuan_var[$i]['yingfaheji']=0;
			$jisuan_var[$i]['gerenshiye']="错误";
			$jisuan_var[$i]['gerenyiliao']="错误";
			$jisuan_var[$i]['gerenyanglao']="错误";
			$jisuan_var[$i]['gerengongjijin']=0;
			$jisuan_var[$i]['daikousui']=0;
			$jisuan_var[$i]['koukuanheji']=0;
			$jisuan_var[$i]['shifaheji']=0;
			$jisuan_var[$i]['danweishiye']="错误";
			$jisuan_var[$i]['danweigongshang']="错误";
			$jisuan_var[$i]['danweishengyu']="错误";
			$jisuan_var[$i]['danweiyanglao']="错误";
			$jisuan_var[$i]['danweiyiliao']="错误";
			$jisuan_var[$i]['danweigongjijin']=0;
			$jisuan_var[$i]['danweiheji']="错误";
			$jisuan_var[$i]['jiaozhongqiheji']=0;
			}
		}
		//print_r($jisuan_var);
	}
	function getShenfenleibie($shenfenleibie){
		$userType=0;
		switch ($shenfenleibie){
			case "实习生";
			$userType=0;
			break;
			case "未缴纳保险";
			$userType=0;
			break;
			case "本市城镇职工";
			$userType=1;
			break;
			case "外埠城镇职工";
			$userType=2;
			break;
			case "本市农村劳动力";
			$userType=3;
			break;
			case "外地农村劳动力";
			$userType=4;
			break;
			case "本市农民工";
			$userType=5;
			break;
			case "外地农民工";
			$userType=6;
			break;
			default:
			$userType=-1;	
		}
		return $userType;
	}
	function jisuan_geren_shiye($shebaojishu,$userType){
		/**
		 * =IF(F2="本市城镇职工",MAX(MIN(E2,12603),1680)*0.2%,
IF(F2="外埠城镇职工",MAX(MIN(E2,12603),1680)*0.2%,
IF(F2="本市农村劳动力",0,
IF(F2="外地农村劳动力",0,
IF(F2="本市农民工",0,
IF(F2="外地农民工",0,"错误"))))))
		 */
		if($userType==1||$userType==2){
			$gerenshiye=$this->max($this->min($shebaojishu,12603),1680)*0.002;
		}else {
			$gerenshiye=0;
		}
		return $gerenshiye;
	}
	function jisuan_geren_yiliao($shebaojishu,$userType){
		/**
		 * =IF(F2="本市城镇职工",MAX(MIN(E2,12603),2521)*2%+3,
IF(F2="外埠城镇职工",MAX(MIN(E2,12603),2521)*2%+3,
IF(F2="本市农村劳动力",MAX(MIN(E2,12603),2521)*2%+3,
IF(F2="外地农村劳动力",MAX(MIN(E2,12603),2521)*2%+3,
IF(F2="本市农民工",0,
IF(F2="外地农民工",0,"错误"))))))
		 */
	    if($userType==1||$userType==2||$userType==3||$userType==4){
			$gerenyiliao=$this->max($this->min($shebaojishu,12603),2521)*0.02+3;
		}else {
			$gerenyiliao=0;
		}
		return $gerenyiliao;
	}
	function jisuan_geren_yanglao($shebaojishu,$userType){
		/**
		 * =IF(F2="本市城镇职工",MAX(MIN(E2,12603),1680)*8%,
IF(F2="外埠城镇职工",MAX(MIN(E2,12603),1680)*8%,
IF(F2="本市农村劳动力",MAX(MIN(E2,12603),1680)*8%,
IF(F2="外地农村劳动力",MAX(MIN(E2,12603),1680)*8%,
IF(F2="本市农民工",MAX(MIN(E2,12603),1680)*8%, 
IF(F2="外地农民工",MAX(MIN(E2,12603),1680)*8%,"错误"))))))
		 */
		$gerenyanglao=$this->max($this->min($shebaojishu,12603),1680)*0.08;
		if($userType==0){
			$gerenyanglao=0;
		}
		return $gerenyanglao;
	}
	function jisuan_danwei_shiye($shebaojishu,$userType){
		/**
		 * =IF(F2="本市城镇职工",MAX(MIN(E2,12603),1680)*1%,
IF(F2="外埠城镇职工",MAX(MIN(E2,12603),1680)*1%,
IF(F2="本市农村劳动力",MAX(MIN(E2,12603),1680)*1%,
IF(F2="外地农村劳动力",MAX(MIN(E2,12603),1680)*1%,
IF(F2="本市农民工",MAX(MIN(E2,12603),1680)*1%,
IF(F2="外地农民工",MAX(MIN(E2,12603),1680)*1%,"错误"))))))
		 */
		$danweishiye=$this->max($this->min($shebaojishu,12603),1680)*0.01;
	    if($userType==0){
			$danweishiye=0;
		}
		return $danweishiye;
	}
	function jisuan_danwei_yiliao($shebaojishu,$userType){
		/**
		 * =IF(F2="本市城镇职工",MAX(MIN(E2,12603),2521)*10%,
IF(F2="外埠城镇职工",MAX(MIN(E2,12603),2521)*10%,
IF(F2="本市农村劳动力",MAX(MIN(E2,12603),2521)*10%,
IF(F2="外地农村劳动力",MAX(MIN(E2,12603),2521)*10%,
IF(F2="本市农民工",2521*1%,
IF(F2="外地农民工",2521*1%,"错误"))))))
		 */
		if($userType==1||$userType==2||$userType==3||$userType==4){
			$danweiyiliao=$this->max($this->min($shebaojishu,12603),2521)*0.1;
		}else{
			$danweiyiliao=2521*0.01;
			
		}
	   if($userType==0){
			$danweiyiliao=0;
		}
		return $danweiyiliao;
	}
	function jisuan_danwei_yanglao($shebaojishu,$userType){
		/**
		 * =IF(F2="本市城镇职工",MAX(MIN(E2,12603),1680)*20%,
IF(F2="外埠城镇职工",MAX(MIN(E2,12603),1680)*20%,
IF(F2="本市农村劳动力",MAX(MIN(E2,12603),1680)*20%,
IF(F2="外地农村劳动力",MAX(MIN(E2,12603),1680)*20%,
IF(F2="本市农民工",MAX(MIN(E2,12603),1680)*20%,
IF(F2="外地农民工",MAX(MIN(E2,12603),1680)*20%,"错误"))))))
		 */
		$danweiyanglao=$this->max($this->min($shebaojishu,12603),1680)*0.2;
	    if($userType==0){
			$danweiyiliao=0;
		}
		return $danweiyanglao;
	}
	function jisuan_danwei_gongshang($shebaojishu,$userType){
		/**
		 * =IF(F2="本市城镇职工",MAX(MIN(E2,12603),1680)*0.8%,
IF(F2="外埠城镇职工",MAX(MIN(E2,12603),1680)*0.8%,
IF(F2="本市农村劳动力",MAX(MIN(E2,12603),2521)*0.8%,
IF(F2="外地农村劳动力",MAX(MIN(E2,12603),2521)*0.8%,
IF(F2="本市农民工",MAX(MIN(E2,12603),2521)*0.8%,
IF(F2="外地农民工",MAX(MIN(E2,12603),2521)*0.8%,"错误"))))))
		 */
		if($userType==1||$userType==2){
			$danweigongshang=$this->max($this->min($shebaojishu,12603),1680)*0.008;
		}else{
		$danweigongshang=$this->max($this->min($shebaojishu,12603),2521)*0.008;
		}
		//echo $danweigongshang."||".$shebaojishu."||".$userType."<br/>";
		if($userType==0){
			$danweigongshang=0;
		}
		return $danweigongshang;
	}
	function jisuan_danwei_shengyu($shebaojishu,$userType){
		/**
		 * =IF(F2="本市城镇职工",MAX(MIN(E2,12603),2521)*0.8%,
IF(F2="外埠城镇职工",0,
IF(F2="本市农村劳动力",MAX(MIN(E2,12603),2521)*0.8%,
IF(F2="外地农村劳动力",0,
IF(F2="本市农民工",MAX(MIN(E2,12603),2521)*0.8%,
IF(F2="外地农民工",0,"错误"))))))
		 */
		//if($userType==1||$userType==3||$userType==5){
			$danweishengyu=$this->max($this->min($shebaojishu,12603),2521)*0.008;
		//}else{
		//   $danweishengyu=0; 	
		//}
	    if($userType==0){
			$danweishengyu=0;
		}
		return $danweishengyu;
	}
	function jisuan_geren_gongjijin($nums){
		return $nums*0.12;
	}
	function jisuan_daikousui($jisuan_var){
		/**
		 * 2. 代扣税的计算方法：工资起征点为2000元，应发合计-个人失业-个人医疗-个人养老-个人公积金-2000元，所得的差									
	   小于等于500，乘以5%											
	   大于500小于等于2000，乘以10%减去25											
	   大于2000小于等于5000，乘以15%再减去125											
	   大于5000小于等于20000，乘以20%再减去375											
	   大于20000小于等于40000，乘以25%再减去1375											
	   大于40000小于等于60000，乘以30%再减去3375											
	   大于60000小于等于80000，乘以35%再减去6375											
	   大于80000小于等于100000，乘以40%再减去10375											
	   大于100000，乘以45%再减去15375						
		 */
		
		$values=$jisuan_var['yingfaheji']-($jisuan_var['gerenshiye']+$jisuan_var['gerenyiliao']+$jisuan_var['gerenyanglao']+$jisuan_var['gerengongjijin']+2000);
                            //echo $jisuan_var['yingfaheji']."/////////////".$values.">>>>>>>>>>>>>>><br />";
		if($values<=500){
			$values=$values*0.05;
		}elseif($values>500&&$values<=2000){
			$values=$values*0.1-25;
		}elseif($values>2000&&$values<=5000){
			$values=$values*0.15-125;
		}elseif($values>5000&&$values<=20000){
			$values=$values*0.2-375;
		}elseif($values>20000&&$values<=40000){
			$values=$values*0.25-1375;
		}elseif($values>40000&&$values<=60000){
			$values=$values*0.3-3375;
		}elseif($values>60000&&$values<=80000){
			$values=$values*0.35-6375;
		}elseif($values>80000&&$values<=100000){
			$values=$values*0.4-10375;
		}elseif($values>100000){
			$values=$values*0.45-15375;
		}
		if($values<0){
			$values=0;
		}
		return $values;
	}
	function jisuan_daikousui_xin2011($jisuan_var){
		/**
		 * 工资起征点为3500元，工资总额减去3500元，减去保险公积金扣款所得的差
		 * 小于等于1500，乘以3%；
大于1500小于等于4500，乘以10%再减去105；
大于4500小于等于9000，乘以20%再减去555；
大于9000小于等于35000，乘以25%再减去1005；
大于35000小于等于55000，乘以30%再减去2755；
大于55000小于等于80000，乘以35%再减去5505；
大于80000，乘以45%再减去13505
		 */
		$values=$jisuan_var['yingfaheji']-($jisuan_var['gerenshiye']+$jisuan_var['gerenyiliao']+$jisuan_var['gerenyanglao']+$jisuan_var['gerengongjijin']+3500);
        if($values<=1500){
			$values=$values*0.03;
		}elseif($values>1500&&$values<=4500){
			$values=$values*0.1-105;
		}elseif($values>4500&&$values<=9000){
			$values=$values*0.2-555;
		}elseif($values>9000&&$values<=35000){
			$values=$values*0.25-1005;
		}elseif($values>35000&&$values<=55000){
			$values=$values*0.3-2755;
		}elseif($values>55000&&$values<=80000){
			$values=$values*0.35-5505;
		}elseif($values>80000){
			$values=$values*0.45-13505;
		}
		if($values<0){
			$values=0;
		}
		return $values;
	}
	function max($num1,$num2){
		if($num1>$num2){
			return $num1;
		}else{
			return $num2;
		}
	}
	function min($num1,$num2){
	   if($num1<$num2){
			return $num1;
		}else{
			return $num2;
		}
	}
	function sumNianSal(&$jisuan_var){
		$cha=0.0;
		if($jisuan_var['yingfaheji']<3500){
			$cha=3500-$jisuan_var['yingfaheji'];
		}
		$pingjun=$jisuan_var[$i]['nianzhongjiang']/12;
	    if($pingjun<=1500){
			$jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.03;
		}elseif($pingjun>1500&&$pingjun<=4500){
			$jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.1-105;
		}elseif($pingjun>4500&&$pingjun<=9000){
			$jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.2-555;
		}elseif($pingjun>9000&&$pingjun<=35000){
			$jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.25-1005;
		}elseif($pingjun>35000&&$pingjun<=55000){
			$jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.3-2755;
		}elseif($pingjun>55000&&$pingjun<=80000){
			$jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.35-5505;
		}elseif($pingjun>80000){
			$jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.45-13505;
		}
		$jisuan_var['shifajinka']=$jisuan_var['nianzhongjiang']-$jisuan_var['niandaikoushui'];
		$jisuan_var['jiaozhongqi']=$jisuan_var['nianzhongjiang'];
		
	}
	function sumErSal(&$jisuan_var){
		/**
		 * $jisuan_var['ercigongziheji']=$addValue;
				$jisuan_var['yingfaheji']=$employ['per_yingfaheji'];
				$jisuan_var['shijiyingfaheji']=$jisuan_var['ercigongziheji']+$jisuan_var['yingfaheji'];
				$jisuan_var['shiye']=$employ['per_shiye'];
				$jisuan_var['yiliao']=$employ['per_yiliao'];
				$jisuan_var['yanglao']=$employ['per_yanglao'];
				$jisuan_var['gongjijin']=$employ['per_gongjijin'];
				$jisuan_var['yikoushui']=$employ['per_daikoushui'];
				//失业	医疗	养老	公积金	应扣税	已扣税	补扣税	2010年1次双薪进卡	缴中企基业合计
		 */
		$values=$jisuan_var['shijiyingfaheji']-$jisuan_var['shiye']-$jisuan_var['yiliao']-$jisuan_var['yanglao']-$jisuan_var['gongjijin']-3500;
		if($values<=1500){
			$values=$values*0.03;
		}elseif($values>1500&&$values<=4500){
			$values=$values*0.1-105;
		}elseif($values>4500&&$values<=9000){
			$values=$values*0.2-555;
		}elseif($values>9000&&$values<=35000){
			$values=$values*0.25-1005;
		}elseif($values>35000&&$values<=55000){
			$values=$values*0.3-2755;
		}elseif($values>55000&&$values<=80000){
			$values=$values*0.35-5505;
		}elseif($values>80000){
			$values=$values*0.45-13505;
		}
		if($values<0){
			$values=0;
		}
		$jisuan_var['yingkoushui']=$values;
		$jisuan_var['bukoushui']=$jisuan_var['yingkoushui']-$jisuan_var['yikoushui'];
		$jisuan_var['shuangxinjinka']=$jisuan_var['ercigongziheji']-$jisuan_var['bukoushui'];
		$jisuan_var['jiaozhongqi']=$jisuan_var['ercigongziheji'];
	}
}

?>