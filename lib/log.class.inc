<?php
class log{
	  private $logdata=array();
      private $beginlogchar="*";
      private $logBeginStr="@@@@@@@@";
      private $beginlog="";
      private $procsesslogchar="@";
      private $sqllogchar="**************";
      private $procsesslog="";
      private $endlogchar="#";
      private $endlog="";
      private $logPath=ERROR_LOG_PATH; 
      public function __construct(){
      $this->logdata['beginTime']=date('Y-m-d H:i:s');
      $this->beginlog=str_repeat($this->beginlogchar,10);
      $this->procsesslog=str_repeat($this->procsesslogchar,10);
      $this->endlog=str_repeat($this->endlogchar,10);
      }
      public function setLogdata($key,$value){
      $this->logdata[$key]=$value;
      }
      public function getLogdata($key){
      if(!$key){
       return "no log keys parameter";
      }
      return  $this->logdata[$key];

      }
      public function write($key=null){
       $date=date("Y-m-d");
       $filepath=ERROR_LOG_PATH."error_log.$date.log";
      if(!is_dir($this->logPath)){
            throw new Exception("{$this->logPath} is a disable dir");

      }

       switch($key){
       case 'option' :
       $logStrArr[]=$this->beginlog.$key.$this->beginlog;
       $logStrArr[] = $this->logBeginStr . ' ' . $this->getLogdata($key) . ' ' . $this->logBeginStr;
       $logStrArr[] = 'Begin Time: ' . $this->logdata['beginTime'];
       break;
       case 'error' :
       $logStrArr[]=$this->beginlog.$key.$this->beginlog;
       $logStrArr[] = $this->logBeginStr . ' ' . $this->getLogdata($key) . ' ' . $this->logBeginStr;
       $logStrArr[] = 'Begin Time: ' . $this->logdata['beginTime']; 
       break;
       case 'error_msg' :
       $logStrArr[]=$this->beginlog.$key.$this->beginlog;
       $logStrArr[] = $this->logBeginStr . ' ' . $this->getLogdata($key) . ' ' . $this->logBeginStr;
       $logStrArr[] = 'Begin Time: ' . $this->logdata['beginTime']; 
       break; 
       case 'sql_msg' :
       $logStrArr[]=$this->sqllogchar.$key.$this->sqllogchar;
       $logStrArr[] = $this->logBeginStr . ' ' . $this->getLogdata($key) . ' ' . $this->logBeginStr;
       $logStrArr[] = 'Begin Time: ' . $this->logdata['beginTime']; 
       break; 
       default :
       $logStrArr[]=$this->beginlog.'LOGS'.$this->beginlog; 
      }
      //print_r($logStrArr);
      $logStr = implode( "\n", $logStrArr );
       error_log( "\n" . $logStr, 3, $filepath );
      }

}
