<?php
   require_once("lib/config.inc");
   require_once("lib/db.inc");
   require_once("common/BaseAction.class.php");
   require_once("common/BaseDao.class.php");
   require_once("common/BaseForm.class.php");
  // global $loginusername;
    /***
     *记录用户名
     */
   // $loginusername = getenv("_byuser");
   //$loginusername="zchao";
   //if(strlen($loginusername)==0)
   //{
    //echo "please login first......";
   // return;
   //}
   /**
    *抓取抛出的异常写入错误日志中
    */
   function fetchException( Exception $exp )
    {
        //ExceptionHandler::reportMini( $exp );

        echo json_encode( 
            array( 'result'=>0, 
                    'error'=> '(' . $exp->getCode() . ') ' . $exp->getMessage() 
            )
        );
        $LOG = new log();
        $date = date('Y-m-d H:i:s');


        $LOG->setLogdata( 'error_code', $exp->getCode() );
        $LOG->setLogdata( 'error_msg', $exp->getMessage() );

        #$LOG->write('error_code');
        $LOG->write('error_msg');
        exit;
    }
    /**
     *抓取错误写入错误日志中
     */
    function fetchError( $errNo, $errStr, $errFile, $errLine )
    {
       if($errNo!=2048&&$errNo!=8){
       $LOG = new log();
       // ErrorHandler::reportMini( array( 'errNo' => $errNo, 'errStr' => $errStr,'errFile' => $errFile, 'errLine' => $errLine ) );
       $errorStr=json_encode(array( 'errNo' => $errNo, 'errStr' => $errStr,'errFile' => $errFile, 'errLine' => $errLine ));
        $LOG->setLogdata( 'error',  $errorStr."" );
        $LOG->write('error');
       }
        return true;
    }
    function dealupload($pid){
	//global $uploadpath, $uploadfname;
	mkdir(UPLOADPATH, 0777, true );
	$fullfilepath = UPLOADPATH.UPLOAD_FILE_NAME.$pid;
	if (!move_uploaded_file($_FILES['plusfile']['tmp_name'], $fullfilepath)){
		print_r($_FILES);print_r($fullfilepath);
		die("UPLOAD FILE FAILED:".$_FILES['plusfile']['error']);
	}
	
	// 格式检查
	$fp = fopen($fullfilepath, 'rb');
	$linenum = 1;
	while (!feof($fp)){
		$line = fgets($fp, 4096);
		$line = trim($line, " \r\n");
		$linenum++;
	}
	fclose($fp);
}
    set_error_handler( 'fetchError' );
    set_exception_handler( 'fetchException' );
?>
